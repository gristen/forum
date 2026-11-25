<?php

use App\Services\SystemMetricsService;
use Livewire\Component;

new class extends Component {

    public array $metrics = [];

    public string $updatedAt = '';

    public function mount(): void
    {
        $this->load();
    }

    /**
     * Вызывается при первом рендере и каждые 3 секунды через wire:poll.
     */
    public function load(): void
    {
        /* Компонент доступен и через /livewire/update, поэтому проверяем право сами. */
        abort_unless(auth()->user()?->hasPermission('admin.access'), 403);

        $this->metrics = app(SystemMetricsService::class)->snapshot();
        $this->updatedAt = now()->format('H:i:s');
    }

    public function bytes(?float $bytes, int $precision = 1): string
    {
        if ($bytes === null) {
            return '—';
        }

        $units = ['Б', 'КБ', 'МБ', 'ГБ', 'ТБ'];
        $index = 0;

        while ($bytes >= 1024 && $index < count($units) - 1) {
            $bytes /= 1024;
            $index++;
        }

        return round($bytes, $index === 0 ? 0 : $precision).' '.$units[$index];
    }

    public function level(?float $percent): string
    {
        return match (true) {
            $percent === null => 'idle',
            $percent >= 85 => 'danger',
            $percent >= 60 => 'warn',
            default => 'ok',
        };
    }

    public function percent(?float $percent): string
    {
        return $percent === null ? '—' : rtrim(rtrim(number_format($percent, 1, '.', ''), '0'), '.');
    }
};
?>

<div class="sys-metrics" wire:poll.3s="load">

    <div class="sys-toolbar">
        <span class="sys-updated">
            <i class="bi bi-arrow-repeat"></i>
            обновлено {{ $updatedAt ?: '—' }}
        </span>
    </div>

    <div class="sys-grid">

        {{-- CPU --}}
        <section class="sys-card">
            <header class="sys-card-head">
                <div class="sys-card-icon cpu"><i class="bi bi-cpu"></i></div>
                <div>
                    <h2 class="sys-card-title">CPU</h2>
                    <p class="sys-card-sub">{{ $metrics['cpu']['count'] ?? 0 }} ядер · WSL VM</p>
                </div>
            </header>

            <div class="sys-card-body">
                @php $cpuUsage = $metrics['cpu']['usage'] ?? null; @endphp

                <div class="sys-value {{ $this->level($cpuUsage) }}">
                    {{ $this->percent($cpuUsage) }}<span>%</span>
                </div>

                <div class="sys-bar">
                    <span class="{{ $this->level($cpuUsage) }}"
                          style="width: {{ $cpuUsage ?? 0 }}%"></span>
                </div>

                <div class="sys-meta">
                    <span>Load avg: {{ implode(' / ', $metrics['cpu']['load'] ?? []) }}</span>
                </div>

                <div class="sys-cores">
                    @foreach($metrics['cpu']['cores'] ?? [] as $index => $core)
                        @php $coreUsage = $core['usage'] ?? null; @endphp

                        <div class="sys-core" title="{{ $core['name'] }}: {{ $this->percent($coreUsage) }}%">
                            <div class="sys-core-track">
                                <span class="{{ $this->level($coreUsage) }}"
                                      style="height: {{ $coreUsage ?? 0 }}%"></span>
                            </div>
                            <small>{{ $index + 1 }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>


        {{-- RAM --}}
        <section class="sys-card">
            <header class="sys-card-head">
                <div class="sys-card-icon ram"><i class="bi bi-memory"></i></div>
                <div>
                    <h2 class="sys-card-title">Память</h2>
                    <p class="sys-card-sub">RAM виртуалки WSL</p>
                </div>
            </header>

            <div class="sys-card-body">
                @php
                    $ramPercent = $metrics['memory']['percent'] ?? null;
                    $swapPercent = $metrics['memory']['swap_percent'] ?? null;
                @endphp

                <div class="sys-value {{ $this->level($ramPercent) }}">
                    {{ $this->percent($ramPercent) }}<span>%</span>
                </div>

                <div class="sys-bar">
                    <span class="{{ $this->level($ramPercent) }}"
                          style="width: {{ $ramPercent ?? 0 }}%"></span>
                </div>

                <div class="sys-meta">
                    <span>{{ $this->bytes($metrics['memory']['used'] ?? null) }}
                        из {{ $this->bytes($metrics['memory']['total'] ?? null) }}</span>
                    <span>свободно {{ $this->bytes($metrics['memory']['available'] ?? null) }}</span>
                </div>

                <div class="sys-meta">
                    <span>Swap</span>
                    <span>{{ $this->bytes($metrics['memory']['swap_used'] ?? null) }}
                        из {{ $this->bytes($metrics['memory']['swap_total'] ?? null) }}
                        ({{ $this->percent($swapPercent) }}%)</span>
                </div>
            </div>
        </section>


        {{-- Диск --}}
        <section class="sys-card">
            <header class="sys-card-head">
                <div class="sys-card-icon disk"><i class="bi bi-device-hdd"></i></div>
                <div>
                    <h2 class="sys-card-title">Диск</h2>
                    <p class="sys-card-sub">Корневой раздел /</p>
                </div>
            </header>

            <div class="sys-card-body">
                @php $diskPercent = $metrics['disk']['percent'] ?? null; @endphp

                <div class="sys-value {{ $this->level($diskPercent) }}">
                    {{ $this->percent($diskPercent) }}<span>%</span>
                </div>

                <div class="sys-bar">
                    <span class="{{ $this->level($diskPercent) }}"
                          style="width: {{ $diskPercent ?? 0 }}%"></span>
                </div>

                <div class="sys-meta">
                    <span>занято {{ $this->bytes($metrics['disk']['used'] ?? null) }}</span>
                    <span>всего {{ $this->bytes($metrics['disk']['total'] ?? null) }}</span>
                </div>

                <div class="sys-meta">
                    <span>свободно</span>
                    <span>{{ $this->bytes($metrics['disk']['free'] ?? null) }}</span>
                </div>
            </div>
        </section>


        {{-- GPU --}}
        <section class="sys-card">
            <header class="sys-card-head">
                <div class="sys-card-icon gpu"><i class="bi bi-gpu-card"></i></div>
                <div>
                    <h2 class="sys-card-title">GPU</h2>
                    <p class="sys-card-sub">
                        {{ $metrics['gpu']['name'] ?? 'NVIDIA' }}
                        @if(! empty($metrics['gpu']['driver']))
                            · драйвер {{ $metrics['gpu']['driver'] }}
                        @endif
                    </p>
                </div>
            </header>

            <div class="sys-card-body">
                @if($metrics['gpu']['available'] ?? false)
                    @php
                        $gpuUsage = $metrics['gpu']['utilization'] ?? null;
                        $vramPercent = $metrics['gpu']['memory_percent'] ?? null;
                    @endphp

                    <div class="sys-value {{ $this->level($gpuUsage) }}">
                        {{ $this->percent($gpuUsage) }}<span>%</span>
                    </div>

                    <div class="sys-bar">
                        <span class="{{ $this->level($gpuUsage) }}"
                              style="width: {{ $gpuUsage ?? 0 }}%"></span>
                    </div>

                    <div class="sys-meta">
                        <span>VRAM</span>
                        <span>{{ $metrics['gpu']['memory_used'] ?? '—' }} / {{ $metrics['gpu']['memory_total'] ?? '—' }} MiB
                            ({{ $this->percent($vramPercent) }}%)</span>
                    </div>

                    <div class="sys-chips">
                        <span class="sys-chip">
                            <i class="bi bi-thermometer-half"></i>
                            {{ $metrics['gpu']['temperature'] ?? '—' }} °C
                        </span>
                        <span class="sys-chip">
                            <i class="bi bi-fan"></i>
                            {{ $metrics['gpu']['fan'] ?? '—' }} %
                        </span>
                        <span class="sys-chip">
                            <i class="bi bi-lightning-charge"></i>
                            {{ $metrics['gpu']['power'] ?? '—' }} W
                        </span>
                    </div>
                @else
                    <div class="sys-empty">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <strong>GPU недоступен</strong>
                            <p>{{ $metrics['gpu']['error'] ?? 'nvidia-smi не вернул данные' }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </section>

    </div>


    {{-- Служебная информация --}}
    <section class="sys-card sys-card-wide">
        <header class="sys-card-head">
            <div class="sys-card-icon info"><i class="bi bi-info-circle"></i></div>
            <div>
                <h2 class="sys-card-title">Система</h2>
                <p class="sys-card-sub">Служебные показатели приложения и сервера</p>
            </div>
        </header>

        <div class="sys-card-body">
            <div class="sys-info-grid">
                <div class="sys-info">
                    <span class="sys-info-label">PHP</span>
                    <span class="sys-info-value">{{ $metrics['system']['php'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Laravel</span>
                    <span class="sys-info-value">{{ $metrics['system']['laravel'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">ОС</span>
                    <span class="sys-info-value">{{ $metrics['system']['os'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">SAPI</span>
                    <span class="sys-info-value">{{ $metrics['system']['sapi'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Часовой пояс</span>
                    <span class="sys-info-value">{{ $metrics['system']['timezone'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Время сервера</span>
                    <span class="sys-info-value">{{ $metrics['system']['server_time'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Uptime</span>
                    <span class="sys-info-value">{{ $metrics['system']['uptime'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Memory limit</span>
                    <span class="sys-info-value">{{ $metrics['system']['memory_limit'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Размер БД</span>
                    <span class="sys-info-value">{{ $metrics['system']['db_size'] ?? '—' }}</span>
                </div>
                <div class="sys-info">
                    <span class="sys-info-label">Процесс</span>
                    <span class="sys-info-value">{{ get_current_user() ?: '—' }}</span>
                </div>
            </div>
        </div>
    </section>

</div>
