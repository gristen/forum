<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Process\Process;

/**
 * Снимок состояния сервера: CPU, память, диск, GPU, служебная информация.
 *
 * Всё читается из /proc и через nvidia-smi (WSL). Любая недоступная
 * метрика возвращается как null — страница не должна падать из-за неё.
 */
class SystemMetricsService
{
    private const CPU_CACHE_KEY = 'system-metrics:cpu';

    private const CPU_CACHE_TTL = 30;

    /** Сколько ждать между двумя замерами /proc/stat, если предыдущего нет. */
    private const CPU_SAMPLE_MICROSECONDS = 200_000;

    public function snapshot(): array
    {
        return [
            'cpu' => $this->cpu(),
            'memory' => $this->memory(),
            'disk' => $this->disk(),
            'gpu' => $this->gpu(),
            'system' => $this->system(),
        ];
    }

    /**
     * Загрузка CPU в процентах.
     *
     * /proc/stat отдаёт накопительные счётчики с момента загрузки, поэтому
     * процент считается как дельта между двумя замерами. Предыдущий замер
     * лежит в кэше; если его нет (первый запрос, сброс кэша) — делаем
     * короткую паузу и снимаем два значения подряд.
     */
    private function cpu(): array
    {
        $now = $this->readCpuStat();
        $previous = Cache::get(self::CPU_CACHE_KEY);

        if (! is_array($previous) || $previous === []) {
            usleep(self::CPU_SAMPLE_MICROSECONDS);
            $previous = $now;
            $now = $this->readCpuStat();
        }

        Cache::put(self::CPU_CACHE_KEY, $now, self::CPU_CACHE_TTL);

        $cores = [];

        foreach ($now as $key => $values) {
            if ($key === 'total') {
                continue;
            }

            $cores[] = [
                'name' => str_replace('cpu', 'ядро ', $key),
                'usage' => $this->cpuUsage($previous[$key] ?? null, $values),
            ];
        }

        $load = sys_getloadavg() ?: [0, 0, 0];

        return [
            'usage' => $this->cpuUsage($previous['total'] ?? null, $now['total'] ?? null),
            'cores' => $cores,
            'count' => count($cores),
            'load' => array_map(static fn ($value) => round((float) $value, 2), $load),
        ];
    }

    /**
     * @return array<string, array{total: int, idle: int}>
     */
    private function readCpuStat(): array
    {
        $result = [];

        $lines = @file('/proc/stat', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [];

        foreach ($lines as $line) {
            if (! preg_match('/^(cpu\d*)\s+(.+)$/', $line, $matches)) {
                continue;
            }

            $values = array_map('intval', preg_split('/\s+/', trim($matches[2])));

            // user nice system idle iowait irq softirq steal
            $result[$matches[1] === 'cpu' ? 'total' : $matches[1]] = [
                'total' => array_sum(array_slice($values, 0, 8)),
                'idle' => ($values[3] ?? 0) + ($values[4] ?? 0),
            ];
        }

        return $result;
    }

    private function cpuUsage(?array $previous, ?array $now): ?float
    {
        if ($previous === null || $now === null) {
            return null;
        }

        $totalDelta = $now['total'] - $previous['total'];
        $idleDelta = $now['idle'] - $previous['idle'];

        if ($totalDelta <= 0) {
            return null;
        }

        return round(max(0, min(100, (1 - $idleDelta / $totalDelta) * 100)), 1);
    }

    private function memory(): array
    {
        $info = [];

        foreach (@file('/proc/meminfo', FILE_IGNORE_NEW_LINES) ?: [] as $line) {
            if (preg_match('/^(\w+):\s+(\d+)/', $line, $matches)) {
                $info[$matches[1]] = (int) $matches[2] * 1024;
            }
        }

        $total = $info['MemTotal'] ?? 0;
        $available = $info['MemAvailable'] ?? ($info['MemFree'] ?? 0);
        $used = max(0, $total - $available);

        $swapTotal = $info['SwapTotal'] ?? 0;
        $swapUsed = max(0, $swapTotal - ($info['SwapFree'] ?? 0));

        return [
            'total' => $total,
            'used' => $used,
            'available' => $available,
            'percent' => $total > 0 ? round($used / $total * 100, 1) : null,
            'swap_total' => $swapTotal,
            'swap_used' => $swapUsed,
            'swap_percent' => $swapTotal > 0 ? round($swapUsed / $swapTotal * 100, 1) : null,
        ];
    }

    private function disk(): array
    {
        $total = (float) (@disk_total_space('/') ?: 0);
        $free = (float) (@disk_free_space('/') ?: 0);
        $used = max(0, $total - $free);

        return [
            'total' => $total,
            'used' => $used,
            'free' => $free,
            'percent' => $total > 0 ? round($used / $total * 100, 1) : null,
        ];
    }

    private function gpu(): array
    {
        $binary = $this->nvidiaSmiBinary();

        if ($binary === null) {
            return ['available' => false, 'error' => 'nvidia-smi не найден'];
        }

        $query = 'name,driver_version,utilization.gpu,memory.used,memory.total,'
            .'temperature.gpu,fan.speed,power.draw';

        try {
            $process = new Process([
                $binary,
                '--query-gpu='.$query,
                '--format=csv,noheader,nounits',
            ]);

            $process->setTimeout(3);
            $process->run();

            if (! $process->isSuccessful()) {
                return [
                    'available' => false,
                    'error' => trim($process->getErrorOutput()) ?: 'nvidia-smi завершился с ошибкой',
                ];
            }

            $line = trim(explode("\n", trim($process->getOutput()))[0] ?? '');

            if ($line === '') {
                return ['available' => false, 'error' => 'Пустой ответ nvidia-smi'];
            }

            $parts = array_map('trim', explode(',', $line));

            $memoryUsed = $this->number($parts[3] ?? null);
            $memoryTotal = $this->number($parts[4] ?? null);

            return [
                'available' => true,
                'name' => $parts[0] ?? null,
                'driver' => $parts[1] ?? null,
                'utilization' => $this->number($parts[2] ?? null),
                'memory_used' => $memoryUsed,
                'memory_total' => $memoryTotal,
                'memory_percent' => ($memoryUsed !== null && $memoryTotal)
                    ? round($memoryUsed / $memoryTotal * 100, 1)
                    : null,
                'temperature' => $this->number($parts[5] ?? null),
                'fan' => $this->number($parts[6] ?? null),
                'power' => $this->number($parts[7] ?? null),
            ];
        } catch (\Throwable $e) {
            return ['available' => false, 'error' => 'Не удалось получить данные GPU'];
        }
    }

    private function nvidiaSmiBinary(): ?string
    {
        $candidates = [
            '/usr/lib/wsl/lib/nvidia-smi',
            '/usr/bin/nvidia-smi',
            '/usr/local/bin/nvidia-smi',
        ];

        foreach ($candidates as $candidate) {
            if (is_executable($candidate)) {
                return $candidate;
            }
        }

        try {
            $process = new Process(['which', 'nvidia-smi']);
            $process->setTimeout(2);
            $process->run();

            $path = trim($process->getOutput());

            return $path !== '' && is_executable($path) ? $path : null;
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * nvidia-smi отдаёт "[N/A]" для неподдерживаемых полей (питание на 4060 в WSL).
     */
    private function number(?string $value): int|float|null
    {
        if ($value === null) {
            return null;
        }

        $value = trim($value);

        if ($value === '' || ! is_numeric($value)) {
            return null;
        }

        return str_contains($value, '.') ? round((float) $value, 2) : (int) $value;
    }

    private function system(): array
    {
        $uptime = (float) (explode(' ', trim(@file_get_contents('/proc/uptime') ?: '0'))[0] ?? 0);

        return [
            'php' => PHP_VERSION,
            'laravel' => app()->version(),
            'os' => php_uname('s').' '.php_uname('r'),
            'sapi' => PHP_SAPI,
            'timezone' => config('app.timezone'),
            'server_time' => now()->format('d.m.Y H:i:s'),
            'uptime' => $this->formatUptime($uptime),
            'memory_limit' => ini_get('memory_limit'),
            'db_size' => $this->databaseSize(),
        ];
    }

    private function formatUptime(float $seconds): string
    {
        $total = (int) $seconds;

        $days = intdiv($total, 86400);
        $hours = intdiv($total % 86400, 3600);
        $minutes = intdiv($total % 3600, 60);

        return trim(($days > 0 ? $days.' д ' : '').$hours.' ч '.$minutes.' мин');
    }

    private function databaseSize(): ?string
    {
        return Cache::remember('system-metrics:db-size', 60, function (): string {
            return $this->readDatabaseSize() ?? '—';
        });
    }

    private function readDatabaseSize(): ?string
    {
        try {
            if (DB::connection()->getDriverName() !== 'mysql') {
                return null;
            }

            $size = DB::selectOne(
                'SELECT SUM(data_length + index_length) AS size
                 FROM information_schema.tables
                 WHERE table_schema = DATABASE()'
            )?->size;

            if ($size === null) {
                return null;
            }

            $size = (float) $size;
            $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
            $index = 0;

            while ($size >= 1024 && $index < count($units) - 1) {
                $size /= 1024;
                $index++;
            }

            return round($size, $index === 0 ? 0 : 1).' '.$units[$index];
        } catch (\Throwable) {
            return null;
        }
    }
}
