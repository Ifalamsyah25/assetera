@inject('preloaderHelper', 'JeroenNoten\LaravelAdminLte\Helpers\PreloaderHelper')

<div class="{{ $preloaderHelper->makePreloaderClasses() }}" style="{{ $preloaderHelper->makePreloaderStyle() }}">
    <style>
        .simple-preloader {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 100px;
        }

        .simple-preloader__spinner {
            width: 46px;
            height: 46px;
            border-radius: 999px;
            border: 3px solid #dbe2ea;
            border-top-color: #334155;
            animation: simple-preloader-spin 0.8s linear infinite;
        }

        @keyframes simple-preloader-spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="simple-preloader" aria-label="Loading">
        <div class="simple-preloader__spinner" aria-hidden="true"></div>
    </div>
</div>
