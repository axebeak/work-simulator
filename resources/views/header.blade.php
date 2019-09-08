<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>@yield('title', 'Симулятор Работы')</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <style>
        #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
        #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1.4em; height: 18px; }
        #sortable li span { position: absolute; margin-left: -1.3em; }
        .info-container {
            border: 1px  #F3E1DD solid;
            border-radius: 15px;
            -webkit-box-shadow: -17px 24px 29px -25px rgba(0,0,0,0.75);
            -moz-box-shadow: -17px 24px 29px -25px rgba(0,0,0,0.75);
            box-shadow: -17px 24px 29px -25px rgba(0,0,0,0.75);
        }
        select {
            border-radius: 10px;
            max-width: 200px;
        }
        input[type=text] {
            border: 0;
            border-bottom: 1px blue solid;
            text-align: center;
        }
        .input-num {
            width: 25px;
        }
        .input-name {
            width: 150px;
        }
        .fa-times:hover {
            color: blue;
        }
        .main-cont {
            overflow-x: auto;
        }
        .info-container {
            min-width: 275px;
            max-width: 300px;
            margin-bottom: 30px;
        }
        .mood-li {
            background-color: #fff;
            font-size: 19px !important;
            min-height: 50px;
            width: 400px;
            padding: 10px 30px 10px 30px;
            text-align: justify;
            white-space: nowrap;
            border: 1px #F3E1DD solid;
            border-radius: 15px;
            -webkit-box-shadow: -1px 6px 5px 0px rgba(0,0,0,0.75);
            -moz-box-shadow: -1px 6px 5px 0px rgba(0,0,0,0.75);
            box-shadow: -1px 6px 5px 0px rgba(0,0,0,0.75);
        }
        .delete-mood-li {
            position: relative;
            left: 325px;
        }
        .button-box {
	        position: relative;
	        bottom: 25px;
	        left: 10px;
        }
        .status-log {
            width: 100%;
            height: 200px;
	        padding: 0.25rem;
	        margin-right: 0;
	        margin-left: 0;
	        border-width: .2rem;
	        border: solid #f7f7f9;
	        overflow: auto;
	        font-size: 12px;
        }
        .arrows {
        	font-size: 9px;
        	position: relative;
        	right: 23px;
        	bottom: 2px;
        }
        </style>
        
        
        <!-- Scripts -->
        <script
			  src="https://code.jquery.com/jquery-3.4.1.min.js"
			  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
			  crossorigin="anonymous"></script>
        <script
			  src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
			  integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
			  crossorigin="anonymous"></script>
		<script>
		    $(document).on('click','.fa-times',
                function(){
                    $(this).parent().remove();
            });
		</script>
			  
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
              <li class="nav-item active">
                <a class="nav-link" href="/">Симуляция Работы</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/actions">Действия</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/moods">Настроения</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/characters">Персонажи</a>
              </li>
            </ul>
          </div>
        </nav>
        <div class=" container-fluid">
            @yield('content')
        </div>
    </body>
</html>