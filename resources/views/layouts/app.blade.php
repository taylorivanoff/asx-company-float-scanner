<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <title>{{ config('app.name') }}</title>

        <link rel="icon" type="image/png" href="favicon.png">
        <link rel="stylesheet" type="text/css" href="css/app.css">

        <script type="text/javascript">
        function popout(link) { 
            if (! window.focus)
                return true;
            var href;
            if (typeof(link) == 'string') 
                href = link;
            else 
                href = link.href; 
            window.open(href, '{{ config('app.name') }}', 'width=380,height=110,scrollbars=no,menubar=no'); 
            return false; 
        }
        </script>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-133486425-5"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());

          gtag('config', 'UA-133486425-5');
        </script>
    </head>
    <body>
        <div id="app">
            <div class="container-md" v-cloak>
                <div class="row">
                    <div class="col">
                        <header class="mt-md-5 pt-md-5">
                            <div class="d-flex">
                                <h1 class="h4 mr-4 d-none d-sm-block">ASX Stock Float Scanner</h1>
                                <div class="mode-toggle" @click="modeToggle" :class="darkDark">
                                    <div class="toggle">
                                        <div id="dark-mode" type="checkbox"></div>
                                    </div>
                                </div>
                            </div>
                        </header>
                    </div>
                </div>
                <div class="row ">
                    <div class="col">
                        <p class="text-monospace d-none d-sm-block mb-md-4">Enter a ASX stock ticker/symbol. <span><small><a href="/" onClick="return popout(this)" class="ml-4 d-none d-sm-inline"><u>Popout Window</u></a></small>
                            </span></p>
                        

                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="{{ mix('/js/app.js') }}"></script>
</html>