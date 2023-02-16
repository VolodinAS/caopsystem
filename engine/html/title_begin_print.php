<!doctype html>
<html lang="ru-RU">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Распечатка документа</title>
	<script src="/engine/js/plugins/jquery/jquery-3.5.1-min.js"></script>
	<script src="/engine/js/plugins/tablesorter/tablesorter.js"></script>
	<script>
	    $(document).ready(function(){
	        $('.minitable').tablesorter({ sortList: [[2,0]] });
	    })
	</script>
	<style>

        @page
        {
            size: auto;   /* auto is the initial value */

            /* this affects the margin in the printer settings */
            margin: 5mm 20mm 5mm 20mm;
        }

        body {
            margin: 0;
			color: #000;
			background-color: #fff;
			font-size: 12pt;
            font-family: "Times New Roman", serif;
		}
        
        .rare-text
        {
            letter-spacing: .1rem;
            line-height: 1.5em;
            word-break: break-word;
            text-align: justify;
        }

		/*body {*/
		/*	-webkit-transform:rotate(-90deg); !*Safari, Chrome*!*/
		/*	-moz-transform:rotate(-90deg); !*Firefox*!*/
		/*	filter:progid:DXImageTransform.Microsoft.BasicImage(rotation=3); !*IE*!*/
		/*}*/

		/*@media print { .rotator {filter:progid:DXImageTransform.Microsoft.BasicImage(Rotation=1)} }*/

		.boldy
		{
			font-weight: bolder;
		}

		.gbuz
		{
			font-size: 14pt;
		}

        .cito-gbuz
        {
            font-size: 10pt;
        }

		.address
		{
			font-size: 10pt;
		}

		.size-20pt
		{
			font-size: 20pt;
		}

		.size-16pt
		{
			font-size: 16pt;
		}

		.size-14pt
		{
			font-size: 14pt;
		}

		.size-12pt
		{
			font-size: 12pt;
		}

		.size-10pt
		{
			font-size: 10pt;
		}

		.size-8pt
		{
			font-size: 8pt;
		}

		.size-9pt
		{
			font-size: 9pt;
		}

		.header
		{
			text-align: center;
			width: 100%;
		}

        .listheader
        {
            font-size: 20pt;
        }

		.cito-listheader
		{
			font-size: 14pt;
		}

		.minitable
		{
			font-size: 10pt;
			border: solid 1px #000000;
			border-collapse: collapse;
		}

		.tablehead
		{
			font-size: 12pt;
			text-align: center;
		}

		.textcenter
		{
			text-align: center;
		}

		.tbc
		{
			border-collapse: collapse;
		}

		.border-bottom
		{
			border-bottom: 1px solid black;
		}
        
        .margin-5
        {
            margin-top: 8px;
            margin-bottom: 8px;
        }

		p
		{
			margin: 0px
		}
		p,span {
			border-bottom: 1px solid black;
			line-height: 20px;
			text-align: justify;

		}
		p>span {
			padding-bottom: 2px;
			vertical-align: top;

		}

		.multyline
		{
			text-align: justify;
			text-decoration: underline;
			text-decoration-skip: none;
		}
        
        .align-right
        {
            text-align: right;
        }
        
        .print_nosology_report
        {
            /*width: 100%;*/
            border-collapse: collapse;
            /*border: solid 1px #000;*/
        }

        /*@media all {
            *, html, body
            {
                margin: 0;
                padding: 0;
            }
        }*/

        .my-valign-top
        {
            vertical-align: top;
        }
        
        .vertical-m
        {
            vertical-align: middle;
        }
        
        .vertical-b
        {
            vertical-align: bottom;
        }
        .bt
        {
            border-top: solid 1px #000;
        }
        
        .lh
        {
            line-height: 10px;
        }

        /*@media print {*/
        /*    body {*/
        /*        margin-top: 5mm;*/
        /*        margin-left: 10mm;*/
        /*        margin-bottom: 5mm;*/
        /*        margin-right: 10mm*/
        /*    }*/
        /*}*/

	</style>
    <link rel="icon" type="image/png" href="/engine/images/logo/logo3_cut_bg.png" />
</head>
<body>


