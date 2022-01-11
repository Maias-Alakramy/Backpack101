@extends(backpack_view('blank'))

@php

    $widgets['before_content'][] = [
        'type' => 'div',
	    'class' => 'row',
	    'content' => [ // widgets 
		  	[ 
		        'type'        => 'jumbotron',
                'heading'     => trans('backpack::base.welcome'),
                'content'     => trans('backpack::base.use_sidebar'),
                'button_link' => backpack_url('logout'),
                'button_text' => trans('backpack::base.logout'),
	    	],
	    	[ 
		        'type'         => 'alert',
                'class'        => 'alert alert-danger mb-2',
                'heading'      => 'Important information!',
                'content'      => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Corrupti nulla quas distinctio veritatis provident mollitia error fuga quis repellat, modi minima corporis similique, quaerat minus rerum dolorem asperiores, odit magnam.',
                'close_button' => true, // show close button or not
	    	],
    	]
    ];
@endphp

@section('content')
    <div class="row">
        <div class="col">
            <!-- Chart's container -->
            <div id="chart" style="height: 300px;"></div>
            <!-- Charting library -->
            <script src="https://unpkg.com/echarts/dist/echarts.min.js"></script>
            <!-- Chartisan -->
            <script src="https://unpkg.com/@chartisan/echarts/dist/chartisan_echarts.js"></script>
            <!-- Your application script -->
            <script>
            const chart = new Chartisan({
                el: '#chart',
                url: "@chart('sample_chart')",
            });
            </script>
        </div>
    </div>
@endsection