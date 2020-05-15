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
                window.open(href, '{{ config('app.name') }}', 'width=380,height=130,scrollbars=no,menubar=no'); 
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
                                <h1 class="h4 mr-4"><a href="/">{{ config('app.name') }}</a> <small class="text-muted h6"><br>Stock scanner for intraday traders</small></h1>

                                <div class="mode-toggle" @click="modeToggle" :class="darkDark">
                                    <div class="toggle">
                                        <div id="dark-mode" type="checkbox"></div>
                                    </div>
                                </div>
                            </div>
                        </header>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p class="text-monospace d-none d-sm-block mb-md-4 p">
                            <span><small><a href="/" onClick="return popout(this)" class="link d-none d-sm-inline"><u>Popout Window</u></a></small></span>

                            <span><small><a href="https://github.com/taylorivanoff/asx-scanner" target="_blank" class="link ml-4 d-none d-sm-inline"><u>Source Code</u></a></small></span>

                            @if (config('feedback.enabled'))
                              <feedback-form />
                            @endif
                        </p>
                    </div>
                </div>
        
                <div class="row my-4">
                    <div class="col text-monospace">
                        <p>
                            <small class="mb-md-4">Enter an ASX listed company to find current share float.</small>
                            <span><small class="text-success text-monospace pt-1 pl-4">* Data from Yahoo Finance</small></span>
                        </p>
                        
                        <stock-select></stock-select>
                    </div>
                </div>

                <div class="row my-4">
                    <div class="col">
                        <p class="text-monospace mb-md-4 p small">ASX tickers gap ups. Above $0.30. Vol. above 100K.  
                            Updates every 30 seconds. Time relative to AEST.</p>

                        <stock-table></stock-table>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <script src="{{ mix('/js/app.js') }}"></script>
</html>