<script>
var base_url="<?php echo base_url()?>";
</script>
<link rel="icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png" />
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<link rel="shortcut icon" href="<?php echo base_url(); ?>images/LConnectt Fevicon.png" type="image/png"/>
<title>L Connectt</title>
<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap.min.css"/>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/font-awesome.min.css"/>
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/AdminLTE.min.css" />
<link rel="stylesheet" href="<?php echo base_url(); ?>dist/css/skins/_all-skins.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/rep-style.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/bootstrap-datetimepicker.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jquery.dataTables.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/jquery-confirm.min.css" />
<!------ For custom-style
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/custom-style.css" />
-->
<script src="<?php echo base_url(); ?>js/jquery-2.2.3.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/typeahead.bundle.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/transition.js"></script>
<script src="<?php echo base_url(); ?>js/jquery-ui.min.js"></script>
<script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>js/jquery.speedometer.js"></script>
<script src="<?php echo base_url()?>js/jquery.jqcanvas-modified.js"></script>
<script src="<?php echo base_url()?>js/excanvas-modified.js"></script>
<script src="<?php echo base_url()?>js/countries1.js"></script>
<link href="<?php echo base_url();?>css/jquery.orgchart.css" media="all" rel="stylesheet" type="text/css" />
<link href="<?php echo base_url();?>css/jquerysctiptop.css" rel="stylesheet" type="text/css">
<!-- edited -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.orgchart.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/moment.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/timezones.full.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-confirm.min.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>js/player.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/ckeditor/ckeditor.js"></script>

<script>window.jQuery || document.write('<script src="<?php echo base_url(); ?>js/jquery-2.2.3.min.js"><\/script>')</script>
<style>

/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media (min-width: 768px){
	.sidebar-mini.sidebar-collapse
	.sidebar-menu>li:hover>a>span:not(.pull-right),
	.sidebar-mini.sidebar-collapse
	.sidebar-menu>li:hover>.treeview-menu {
		width: 200px !important;
	}

}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
.skin-blue .main-header .logo{
	background: linear-gradient(to right, #1e282c 100%,#b5000a 100%);
}
.skin-blue .main-header .navbar{
	background: linear-gradient(to right, #1e282c 3%,#b5000a 14%);
}
.sidebar-menu>li>a {
    padding: 11px 5px 11px 15px;
    display: block;
}
.bootstrap-datetimepicker-widget table td span{
	height: 20px;
	line-height: 20px;
}
.bootstrap-datetimepicker-widget a[data-action]{
	padding: 0px 0;
}
#questionlist li,
.ui-sortable .ui-sortable-handle{
	cursor: move;
}
#orgChartContainer .checkbox.custom.pull-right{
	    border: 1px solid #ccc;
		border-radius: 5px;
		cursor: pointer;
}
.loader {
    position: fixed;
    left: 0px;
    top: 0px;
    width: 100%;
    height: 100%;
    z-index: 9999;
    background: url('<?php echo base_url();?>images/hourglass.gif') rgb(249,249,249);
	background-size: 80px 80px;
    background-repeat: no-repeat;
	background-position: center;
    opacity: .9;
}
body{
	overflow-y:auto;
}
body .modal{
	overflow-x: hidden;
    overflow-y: auto;
}
.lc-logo-mini{
	width:32px
	}
.lc-logo-lg{
	width:200px
	}
.main-header .navbar-custom-menu,
.main-header .navbar-right {
    padding: 7px;
}
aside.main-sidebar{
	margin-top:68px;
	margin-bottom:88px;
	position:fixed;
}
.main-sidebar, .left-side {
	    padding-top: 0px;
}
.main-header {
    position: fixed;
	width: 100%;
}
.main-header .logo{
	height: 68px;
}
.accessLabel {
    font-size: 14px;
    margin: 15px;
    color: #fff;
}
.pull-left.image {
    overflow: hidden;
    border-radius: 50%;
}
.module-info {
    width: 150px;
}
.module-info hr{
	margin:6px 0px;
}
.user-panel>.info{
	position: initial;
	margin-right: 15px;
}
.user-panel>.edit{
	padding: 10px;
}
.user-panel {
    overflow: inherit;
	padding: 0px;
}
.content-wrapper.body-content{
	position: relative;
    top: 65px;
}

#adminAvt{
	height: 44px;
    width: 44px;
}
.user-panel .accessLabel{
	font-size: 16px;
	margin: 0px;
	margin-right: 15px;
	color: #fff;
	font-weight: 700;
	margin-top: 12px;
}
.user-panel li{
	line-height:15px;
}
.leadsrcname {
    margin-left: 72px;
	}
/*tooltip*/

.toolTipStyle .tooltip-inner {
    min-width: 550px; /* the minimum width */
	text-align: left;
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media (max-width: 400px){
	content-wrapper.body-content {
		top: 120px;
	}
}

/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
.addExcel{
	position:relative;
	bottom:28PX;
	right:10px;
	float:right;
	text-decoration:none;
}
.addPlus{position:relative;bottom:28PX;right:0px;float:right;text-decoration:none;}

.btn,.btn-default{
	background: #b5000a !important;
	background-color: #b5000a !important;
	color:#fff !important;
	cursor:pointer;
	border:none
	}
.btn:hover,.btn-default:hover{
	background: #b5000a !important;
	background-color: #b5000a !important;
	color:#fff !important;
	cursor:pointer;
	}
.modal-dialog{
	margin-top: 80px;
}
.errMessage{
	color:red;
}
.modal-dialog .modal-header div,
.modal-dialog .modal-body div,
.modal-dialog .modal-footer div{
	padding-right:0px;
	/* padding-left:0px; */
}
.lc_align_right{
	text-align:right;
}
.lc_align_left{
	text-align:left;
}
.chatform table.checkbox,
.chatform table.checkbox tr{
	width:100%;
}
.chatform table.checkbox tr td{
	text-align:center;
}
.chatform table.checkbox input[type=checkbox]{
	margin-left: 0px;
	position: relative;
}
.borderless td, .borderless th {
        border-top: 0px solid #ddd !important;
}
.content-wrapper{
	background:white;
}
.search{
		position: absolute;
		right: 45px;
		top: 4px;
		background: black;
}
.nav.nav-pills.nav-stacked{
	width: 25%;
	float: left;
}
.tab-content table.table{
	margin-top: 0px!important;
}
.tab-content{
	width: 70%;
	float: right;
}
.data-table .row {
	min-height: 40px;
	border: 1px solid #ccc;
	box-shadow: 0px 3px 10px #ccc;
	padding: 8px 0px;
	transition: all 0.5s ease-in-out;
}
.data-table .glyphicon {
	position: absolute;
	margin-top: -5px;
	font-size: large;
	border: 1px solid;
	padding: 5px;
}
.table .glyphicon {
	margin-top: -4px;
	font-size: small;
	border: 2px solid;
	padding: 5px;
	border-radius:4px;
}
.table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
	padding:5px;
}
a{cursor: pointer;}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@keyframes dropHeader {
	0% {
	transform: rotateY(90deg);
	}
	100% {
	transform-origin: left;);
	}
}

/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
.animate-active {
	animation-name: dropHeader;
	animation-iteration-count: 1;
	animation-timing-function: ease-out;
	animation-duration: 1s;
}
.app-modal-window .modal-dialog {
  width: 70%;
  margin-top:42px;
}
.label1{
	padding: 10px;
}
.contrl{
	border-radius: 5px;
	margin:2px;
}
.info-icon img,
.addPlus img,
.addBtns img{
	border-radius:4px;
}

.table_header{
	padding:14px;font-size:14px;
}
input[type=button], input[type=submit]{
		margin:0;
}
.form-control{
	margin-bottom:5px;
	border-radius:5px;
}
.table.table{
	margin-top:0!important;
}
.modal-backdrop{
	z-index:-1;
}
.error-alert{
	color:red;
}
.header1{
	background:rgb(30, 40, 44);
	padding:2px;
}

.header1 .aa{
	padding-top: 6px;
	width: 33.33%;
	float: left;
	position: relative;
	height: 41px;
	line-height: 25px;
}
.header1 .aa h2{
	font-size:28px;
}
.pageHeader1{
	text-align:center;
	color:white;
	height:41px;
	font-size:22px;
}
.pageHeader1 h2{
	margin-bottom: 0;
	margin-top: 0;
}
.column{
	padding:0;
}
.addExcel,
.addPlus{
	bottom: 0;
}

.content-wrapper.body-content section.row{
	height:46px;
}

.info-icon div{
	margin-left: 14px;
}
.sidebar{
	margin-top: 0px;
}
.main-sidebar{
	z-index:0;
}
.modal-dialog{
	margin-top: 110px;
}
.modal-header{
	background: #B5000A;
	color: white;
}
.close{
	color: white;
	opacity: 1;
}
.style_video{
	margin-top: -31px;
    margin-left: 64px!important;
    color: white;
}
.style_video span:hover{
	background: white!important;
	color: #B5000A;
	border: 1px solid #B5000A;
}
.style_video span{
	background: #B5000A;
    padding: 8px;
    border-radius: 5px;
}
.info_icon{
	margin-top: 24px;
}
.info_icon img{
	border-radius: 5px;
}
.modal_video{
	position: fixed;
	width: 50%;
	height: auto!important;
	left: 370px;
	top: 138px;
	z-index: 8080;
	box-shadow: rgb(131, 120, 120) -1px -1px 8px 3px;
}
.ui-resizable{
	position: fixed!important;
}
.video_header{
	background: #B5000A;
	color: white;
	padding: 10px;
}
.video_header h3{
	padding-top: 5px;
}
.video_play{
	padding: 0px;
}
.video_cls_button{
	float: right;
    position: absolute;
    right: 25px;
    font-size: 24px;
    margin-top: 0px;
}
.video_cls_button:hover{
	cursor: pointer;
}
.video_popover hr{
	margin-top: 5px;
	margin-bottom: 5px;
}
.animate{
	-webkit-animation: animatezoom .6s;
	animation: animatezoom .6s;
}
@-webkit-keyframes animatezoom{
	from { -webkit-transform: scale(0) }
	to { -webkit-transform: scale(1) }
}
@keyframes animatezoom{
	from { transform: scale(0) }
	to { transform: scale(1) }
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
	.dataTables_filter{
		width:100% !important;
	}
	.aa h2{
		font-size:15px;
		padding-left: -10px;
		margin-top: 9px;
	}
	.addExcel{
		margin-right: -14px;
	}

	.addBtns{
		margin-right: -19px;
	}
	.addPlus{
		margin-right: 30px;
	}

	.sidebar{
			margin-top: 56px;
	}
	.main-sidebar{
		z-index:0;
	}
	.modal-dialog{
		margin-top: 175px;
	}
	.main-header .sidebar-toggle{
		padding: 13px 15px;
	}
	.accessLabel{
		margin: 5px;
	}
	.navbar-custom-menu .navbar-nav>li>a{
		padding-top: 5px;
	}
	#progress{
		text-align: center;
	}
	#accept{
		text-align: center;
	}
	.downbtn {
		margin-top: 15px;
	}
	.nav.nav-tabs.tabs-left li a{
		margin-left: -30px;
	}
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media only screen and (min-device-width: 768px) and (max-device-width: 1024px){
	.aa h2{
		font-size:22px;
		margin-top: 6px;
	}
	.modal-dialog{
		margin-top: 175px;
	}
	.main-sidebar{
		z-index:0;
	}
	#progress{
		text-align: center;
	}
	#accept{
		text-align: center;
	}
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media only screen and (min-device-width: 340px) and (max-device-width: 632px){
	.dataTables_filter{
		width:100% !important;
	}

	.aa h2{
	font-size:16px;
	padding-left: -10px;
	margin-top: 9px;
	}
	.addPlus{
	margin-right: 30px;
	}
	.addExcel{
	margin-right: -14px;
	}

	.addBtns{
	margin-right: -19px;
	}

	.sidebar{
		margin-top: 56px;
	}
	.main-sidebar{
	z-index:0;
	}
	.modal-dialog{
	margin-top: 175px;
	}
	#progress{
	text-align: center;
	}
	#accept{
	text-align: center;
	}
	.downbtn {
    margin-top: 15px;
	}

	.custom-alert.alert.row {
		width: 94% !important;
		margin: 20% 3% !important;
	}
	#execption_custom_alert .alert.row{
		width: 94% !important;
	}
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@media only screen and (min-device-width: 1024px) and (max-device-width: 1366px){
	.info_table{
		width: 44.333333%;
	}
	.info_table1{
		width: 54.666667%;
	}
	#progress{
	text-align: left;
	}
	#accept{
	text-align: left;
	}
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
.filter_select{
margin-top: 16px;
}
.filter_label{
	margin-top: 25px;
}

option:hover {
background-color:yellow;
}

.opportunities_modal-dialog{
	width: 50%;

}
.select_op{
	margin-bottom: 6px;
	margin-top: 23px;
}
.none{
	display:none;
}
.apport_label{
	padding:0px;
}

.apport_label label{
	font-weight:bold!important;
}
.info_table .row{
	border-bottom:1px solid black;
}
.info_table {margin-bottom: 4px;
}
#dd{
    position: absolute;
    right: 0px;
    top: 11px;
}
.form-control[readonly]{
	background:white;
}
.filter_select{
	margin-top: 16px;
}
.filter_label{
	margin-top: 25px;
}
.opp_form{
	margin-top:15px;
}
.tab_countstat{
	width:100%;
	margin-top: 6px;
}
#orgChart{
    width: auto;
    height: auto;
}

#orgChartContainer{
    width: 100%;
    overflow: auto;
}
#mapname,#edit_mapname{
	width: 100%;
	height: 150px;
	border: 1px;
	position: relative;
	overflow: hidden;
	margin-bottom: 12px;
}
.btn_log{
	margin-bottom: 5px;
}
.lead_opper{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom:0;
}
.lead_view{
	background-color:#c1c1c1;
	padding: 10px 12px;
}
.lead_address{
	background-color:#c1c1c1;
	padding: 10px 12px;
	margin-bottom: 17px;
	margin-top: 6px;
}
/*------------*Calendar style-------------*/
.ui-datepicker-header .ui-datepicker-title{
	    text-align: center;
		font-size: 16px;
		border-bottom: 2px solid rgb(181, 0, 10);
		color: #B5000A;
}
#ui-datepicker-div table.ui-datepicker-calendar tr td:hover{
	background: #ccc;
}
#ui-datepicker-div table.ui-datepicker-calendar tr td,
#ui-datepicker-div table.ui-datepicker-calendar tr th{
	width:20px;
	height:20px;
	text-align:center;
	border-radius: 10px;
}
#ui-datepicker-div table.ui-datepicker-calendar,
#ui-datepicker-div table.ui-datepicker-calendar tr{
	width:100%;
}
#ui-datepicker-div td a.ui-state-highlight{
	color: #B5000A;
	font-size: 16px;
    font-weight: bold;
}
#ui-datepicker-div .ui-datepicker-month{
	outline: none;
    border: none;
	margin-left: 29px;
}
#ui-datepicker-div{
	background: white;
    border: 5px solid rgb(181, 0, 10);
    width: 227px;
	padding:10px;
	border-radius: 5px;
}
.ui-datepicker-header.ui-widget-header .ui-datepicker-next{
	float: right;
}
.ui-datepicker-header.ui-widget-header .ui-datepicker-prev{
	float: left;
}
.col-xs-9.tab-col .table tr th,
.col-xs-9.tab-col .table tr td,
.col-lg-12.column .table tr th,
.col-lg-12.column .table tr td{
 text-align:left ;
  vertical-align: middle;
}
.col-xs-9.tab-col .table tr th:first-child,
.col-xs-9.tab-col .table tr td:first-child,
.col-xs-9.tab-col .table tr th:last-child,
.col-xs-9.tab-col .table tr td:last-child,
.col-lg-12.column .table tr th:first-child,
.col-lg-12.column .table tr td:first-child,
.col-lg-12.column .table tr th:last-child,
.col-lg-12.column .table tr td:last-child{
 text-align:center;
}
.col-xs-9.tab-col .table tr:nth-child(even),
.col-lg-12.column .table tr:nth-child(even){
 background: rgba(204, 204, 204, 0.08);
}
.col-xs-9.tab-col .table tr:hover,
.col-lg-12.column .table tr:hover{
 background: rgba(204, 204, 204, 0.58);
}
.modal button.btn {
    margin-top: 0px;
}
.input-group.date{
	    margin-bottom: 5px;
}
.glyphicon{
	cursor: pointer;
}
.zoom{
	width: 45px;
    margin-top: 43px!important;
    position: fixed;
    right: 33px;
    z-index: 999;
    background: #ccc;
    border-radius: 5px 5px;
}
.zoom .glyphicon.glyphicon-zoom-in{
	margin-left:4px;
}
.zoom .glyphicon{
	margin-top: -4px;
    font-size: small;
    border: 2px solid;
    padding: 5px;
    margin-right: 4px;
    border-radius: 4px;
}

#orgChart td{
	width:1%!important;
}
.orgChart{
	/* zoom: .8;
    -moz-transform: scale(.8);
    transform: scale(.8); ----Standard Property ---------
    -webkit-transform: scale(.8);
    -o-transform: scale(.8); */

    -moz-transform-origin: 0 0;
    -o-transform-origin: 0 0;
    -webkit-transform-origin: 0 0;
    transform-origin: 0 0;  /* Standard Property */
}
.addArrt{
	clear: both;
	background: #eee;
	padding: 3px 5px;
	top: 6px;
	position: relative;
}
.modal h4{
	text-transform:capitalize;
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
@-moz-document url-prefix() {
	div.orgChart tr.lines td.top {
		border-top : 2px dashed black;
	}

	div.orgChart tr.lines td.left {
		border-right : 2px dashed black;
	}
}
/* ------------------------------------------------------------------------------- */
/* ------------------------------------------------------------------------------- */
.modal .btn:focus,.modal .btn:active{
	border: 1px solid #337ab7;
}
.dataTables_filter{
	width:50%;
	margin-right: 15px;
}
.dataTables_filter input{
		width:80%;
	    background: url("<?php echo base_url(); ?>images/Search-Engine-Optimization.jpg");
		background-position: left top;
		background-size: contain;
		background-repeat: no-repeat;
		padding-left: 40px;
		padding-right: 40px;
}
.dataTables_filter label{
	width:100%;
}
.dataTables_wrapper{
	    margin-top: 5px;
}
.dataTables_length select,
.dataTables_filter input{
	height:30px;
	border: 1px solid #b5000a;
	border-radius: 5px;
}
/* ************************************* */
.user-hierarchy div.orgChart h2{
	    word-wrap: break-word;
}
table.dataTable thead .sorting,
table.dataTable thead .sorting_asc,
table.dataTable thead .sorting_desc{
	    background-size: 15px !important;
}
/* ************************************* */
/* ------------------------------------------------------------- user page styles ------------------------------------------- */

        .userpage .mod{margin:4px;}
        .userpage .plugn{height: 21px;}
		.userpage .plugins{
			padding:4px;
		}
		.userpage .text_c{
			text-align: center;
		}
		.userpage .col_wed{
			padding-left: 34px;
		}
		.userpage .col_thu{
			padding-left: 21px;
		}
		.userpage .col_fri{
			padding-left: 3px;
		}
		.userpage .col_sat{
			padding-left: 27px;
		}
		.userpage .work_h{
			background: #808080;
			color: white;
			margin-bottom: 3px;
		}
		.userpage #targetCurrency,.userpage #e_targetCurrency{
			padding-right: 0px;
			padding-left: 8px;
		}

		.userpage .modal{
			overflow-y:scroll;
		}
		.userpage .sec4{
			margin-bottom:8px;
		}
		.userpage #clientel_ind{
			width:129px;
		}
		.userpage #elg_product{
			width:129px;
		}
		.userpage .ui-datepicker-month{
			margin-left: 19px!important;
			border: 1px solid lightgrey!important;
			border-radius: 5px!important;
			margin-right: 2px!important;
		}
		.userpage .ui-datepicker-year{
			border-radius: 5px;
			border-color: lightgrey;
		}

		.userpage .multiselect_pro,
        .userpage .multiselect_loc,
        .userpage .multiselect_indu,
        .userpage .multiselect_cur .ofc_loc,
        .userpage .multiselect_pro1,
        .userpage .multiselect_loc1,
        .userpage .multiselect_view,
        .userpage .multiselect_indu1,
        .userpage .multiselect_cur1 .ofc_loc,
        .userpage .multiselect.ofc_loc{
			opacity: 1;
			margin-bottom:4px;
		}

		.userpage .multiselect{
			height: 60px;
			overflow: auto;
			border: 1px solid #ccc;
			border-radius: 5px;
		}
		.userpage .view_mul{
			height: 60px;
			overflow: auto;
			border: none!important;
			border-radius: 5px;
		}
		.userpage .multiselect ul,
        .userpage .multiselect1 ul,
        .userpage .multiselect12 ul,
        .userpage .multiselect_pro ul,
		.userpage .multiselect_loc ul,
		.userpage .multiselect_indu ul,
		.userpage .multiselect_cur ul{
				padding: 0px;
		}
		.userpage .multiselect ul li.sel,
        .userpage .multiselect1 ul li.sel,
        .userpage .multiselect12 ul li.sel{
				background: #ccc;
		}
		.userpage .multiselect ul li,.userpage .multiselect1 ul li{
				padding: 0 10px;
				text-align: left;
		}
		.userpage .multiselect1,
        .userpage .multiselect_pro,
        .userpage .multiselect_loc,
        .userpage .multiselect_indu,
        .userpage .multiselect_cur,
        .userpage .multiselect_pro1,
        .userpage .multiselect_loc1,
        .userpage .multiselect_indu1,
        .userpage .multiselect_cur1{
			height: 60px;
			overflow: auto;
			border: 1px solid #ccc;
			border-radius: 5px;
		}

		.userpage .multiselect12{
			height: 60px;
			overflow: auto;
			border-radius: 5px;
		}

		.userpage .multiselect12 ul li,
        .userpage .multiselect_pro ul li,
		.userpage .multiselect_loc ul li,
		.userpage .multiselect_indu ul li,
		.userpage .multiselect_cur ul li{
				padding: 0 10px;
				text-align: left;
		}

		.userpage .multiselect_view,
        .userpage .multiselect_vindu1,
        .userpage .multiselect12 {
			height: 60px;
			overflow: auto;
			border-radius: 5px;
		}
		.userpage #currency_value_views .prod_leaf_node{
			font-weight:bold!important;
		}
		.userpage .multiselect_pro1 ul,
		.userpage .multiselect_loc1 ul,
		.userpage .multiselect_view ul,
		.userpage .multiselect_indu1 ul,
		.userpage .multiselect_vindu1 ul,
		.userpage .multiselect_cur1 ul{
				padding:0px;
		}
		.userpage .multiselect_pro1 ul li,
		.userpage .multiselect_loc1 ul li,
		.userpage .multiselect_view ul li,
		.userpage .multiselect_indu1 ul li,
		.userpage .multiselect_vindu1 ul li,
		.userpage .multiselect_cur1 ul li{
				padding: 0 10px;
				text-align: left;
		}
		.userpage .mobile_opt{
			width:12%;
		}
		.userpage .mobile_type{
			margin-left: -12px;
			width: 14%;
		}
		.userpage #mobile, #mob_edit{
			width: 139px!important;
		}
		.userpage #add_more{
			margin-left: 12px;
		}
		.userpage li{
			list-style:none;
			vertical-align: top;
		}
		.userpage .contact_cat,.contact_cat1{
			margin-bottom: 8px;
		}
		.userpage mob_err{
			font-size:13px;
		}
		.userpage .email_type {
			margin-left: -12px;
			width: 17%;
		}
		.userpage .email_opt{
			width: 13%;
		}
		.userpage span .fa-plus-circle{
			color: #B5000A;
			margin-top: 6px;
			font-size: 18px;
		}
		.userpage span .fa_add{
			margin-left: 18px;
			color: #B5000A;
			margin-top: 6px;
			font-size: 18px;
		}
		.userpage .col_fa{
			width:0;
			padding-left: 1px;
		}
		.userpage .off_loc{
			margin-left: 39px;
		}
		.userpage .multiselect_cur{
			margin-bottom: 6px;
		}
		@media only screen and (min-device-width: 320px) and (max-device-width: 480px){
			.userpage .mobile_opt {
				width: 100%;
			}
			.userpage .mobile_type {
				margin-left: 0;
				width: 100%;
			}
			.userpage #mobile,
            .userpage #mob_edit{
				width: 100%!important;
			}

		}
		@media only screen and (min-device-width: 340px) and (max-device-width: 632px){
			.userpage .mobile_opt {
				width: 100%;
			}
			.userpage .mobile_type {
				margin-left: 0;
				width: 100%;
			}
			.userpage #mobile,
            .userpage #mob_edit{
				width: 100%!important;
			}
            /*Opportunity details page */
            .static{
        		position: fixed;
        		bottom: 8px;
        		width: 100%;
        		margin: 0 0 0 0 !important;
           	}
		}
		.userpage #currency_value_list1 .col-md-5,
        .userpage #currency_value_list .col-md-5 {
			border: 1px solid #ccc;
			margin-bottom: 8px;
			border-radius: 5px;
			margin-right: -12px;
			margin-left: 55px;
			padding: 0px;
		}
		.userpage #currency_value_list1 .col-md-6(even),
        .userpage #currency_value_list .col-md-6(even){
			margin-right:5px;
		}
		.userpage .without-curr{
			padding-left: 0px;
			height: 112px;
			overflow: auto;
		}
		.userpage .highlight{
			color:#B5000A;
			font-weight: bold !important;
			width: 100%;
			background: #eee;
		}
		.userpage .highlight.error{
			color:#FFF;
			background:red;
		}
		.userpage .pro_cur_user{
			height:200px;
			overflow-y:auto;
		}
		.userpage #e_additional{
			margin-top: 12px;
		}
		.userpage .error_pro{
			text-align: center;
		}
		.userpage #error,
        .userpage #error1,
        .userpage #error_add,
        .userpage #error_add1{
			color:red;
			font-weight: bold;
		}
		.userpage .modal button.btn {
			margin-left: 10px;
		}
		.userpage .error_mod,.error_mod1{
			text-align:center;
		}
		.userpage .view_row1{
			margin-top: 5px;
		}
		.userpage #currency_value_views .col-md-5{
			margin-bottom: 8px;
			border-radius: 5px;
			margin-right: -12px;
			margin-left: 55px;
			padding: 0px;
		}
		.userpage #currency_value_views .col-md-6(even){
			margin-right:5px;
		}
		.userpage .page_num{
			float: left;
			word-spacing: 1px;
			font-weight: 600;
		}
		.userpage .loader1 {
			position: fixed;
			left: 0px;
			top: 0px;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background: url('<?php echo base_url();?>images/hourglass.gif') rgb(249,249,249);
			background-size: 80px 80px;
			background-repeat: no-repeat;
			background-position: center;
			opacity: .9;
		}
		.userpage .cur_fa{
			font-size:8px;

		}
		.userpage .view_label{
			font-weight:bold!important;
		}
		.userpage .dataTables_length{
			margin-top: 5px;
			margin-left: 4px;
		}
		.userpage .dataTables_filter{
			margin-top: 5px;
			margin-bottom: -1px;
			margin-right: 5px;
		}
		.userpage .alert_foot{
			padding: 8px;
			text-align: center;
		}
		.userpage .alert_body{
			height: 100px;
		}
		.userpage .alert_modal{
			    width: 25%;
		}
		.userpage .alert_col{
			margin-top: 27px;
			margin-left: 51px;
		}
		.userpage .mob_add{
			    margin-left: 9px;
		}
		.userpage .mob_text{
			    margin-left: 0px;
		}
		.userpage .mob_res{
			    margin-left: -6px;
		}
		.userpage .email_cat_edit,.userpage .email_cat {
			margin-bottom: 8px;
		}
        .userpage .sell_multiselect ul li{
			padding: 0 10px;
			text-align: left;
		}
		.userpage .sell_multiselect{
			height: 60px;
			overflow: auto;
			border: 1px solid #ccc;
			border-radius: 5px;
		}
        .userpage .sell_multiselect ul{
			padding: 0px;
		}
/* ---------------tab-content css------------------- */
.tab-content {
    width: 100%;
    float: right;
}
.item.ui-sortable{
	padding: 15px;
}
.tabs-left, .tabs-right {
  border-bottom: none;
  padding-top: 2px;
}
.tabs-left {
  border-right: 1px solid #ddd;
}
.tabs-right {
  border-left: 1px solid #ddd;
}
.tabs-left li, .tabs-right li {
  float: none;
  margin-bottom: 2px;
}
.tabs-left li {
  margin-right: -4px;
}
.tabs-right li {
  margin-left: -1px;
}
.tabs-left li.active a,
.tabs-left li.active a:hover,
.tabs-left li.active a:focus {
  border-bottom-color: #ddd;
  border-right-color: transparent;
}

.nav.nav-tabs.tabs-left li{
	min-height:35px;
	line-height:48px;
}
.verticle-tab{
	height: 500px;
	overflow: auto;
}
ul.nav.nav-tabs.tabs-left{
	min-height: 500px;
}
.nav.nav-tabs.tabs-left li .pull-left{
	width:20%;
	padding-left: 10px;
}
.nav.nav-tabs.tabs-left li .pull-right{
	width:80%;
}
.nav.nav-tabs.tabs-left li a{
	position: initial;
}

.tab-col{
	padding:0;
}
/* ---------------------------------- */


.item.ui-sortable{
	padding: 15px;
}


.li-shortable{
	min-height: 50px;
    border: 1px solid #ccc;
    box-shadow: 0px 3px 12px #ccc;
    padding: 5px;
}

.clear-both{
	clear:both
}
/* --------------tree-view style----------------------- */
.tree-view ul{
		padding-left:20px;
		border-left: 1px dotted;
		list-style-type: none;
	}
	.tree-view ul.mytree{
		border-left: 0px;
	}
	.tree-view ul li label{
		margin-bottom: 0px;
	}
	.dash-left .glyphicon {
		position: absolute;
	}
	.dash-left{
		margin-left: -17px;
		float: left;
		position: absolute;
	}

/* --------------multiselect style----------------------- */
.multiselect.disable{
	background-color: #eee;
    opacity: 1;
}
.multiselect{
	    height: 83px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
}
.multiselect ul{
	    padding: 0px;
}
.multiselect ul li.sel{
	    background: #ccc;
}
.multiselect ul li{
	    padding: 0 10px;
}
.highlight{
	color:#B5000A;
	font-weight: bold !important;
	width: 100%;
	background: #eee;
}
.highlight.error{
	color:#FFF;
	background:red;
}
/* -----------------------Team Page Css starts----------------------------- */

.admin-team-page ul {
	list-style-type: none;
}
.admin-team-page .section2,
.admin-team-page .section3,
.admin-team-page .section4,
.admin-team-page .section5,
.admin-team-page .section6{
	display:none;
}
.admin-team-page #currency_value_list .col-md-5,
.admin-team-page #currency_value_listE .col-md-5	{
	border: 1px solid #ccc;
	margin-bottom: 8px;
	border-radius: 5px;
	margin-right: 6px;
	margin-left: 27px;
	padding: 0px;
}
.admin-team-page #currency_value_list .col-md-6(even),
.admin-team-page #currency_value_listE .col-md-6(even){
	margin-right:5px;
}
.admin-team-page .without-curr{
	padding-left: 0px;
	height: 112px;
	overflow: auto;
}
.admin-team-page .view_secton ol li {
	font-size: 12px;
}
.admin-team-page #view_modal table td{
	text-align: left;
}
.admin-team-page #view_modal .fa.fa-level-up {
    -ms-transform: rotate(90deg); /* IE 9 */
    -webkit-transform: rotate(90deg); /* Chrome, Safari, Opera */
    transform: rotate(90deg);
}
.admin-team-page #view_modal table h4{
	margin: 0px;
    margin-bottom: 10px;
    padding: 5px 0px 5px 8px;
    background: #ccc;
    box-shadow: 0px 2px 4px;
}
.admin-team-page #view_modal i.fa {
    margin-right: 5px;
}
/* -----------------------Team Page Css ends----------------------------- */

#holidayListAdd li{
		border-bottom: 1px solid #ccc;
		margin-bottom: 5px;
}
#holidayListAdd li a.glyphicon.glyphicon-remove-circle {
	float: right;
}
#holidayListAdd{
	margin-top:10px;
}
#addmodal .glyphicon-plus-sign {
	border: 1px solid;
	padding: 8px;
	border-radius: 0 5px 5px 0px;
	position: absolute;
	right: 0;
	background: #fff;
}
#edit_okmap{
	margin-top: 6px;
}
.tab-content table.table{
	margin-top:5px!important;
}
.pre{
	background: transparent;
	border: none;
	margin: auto;
	padding: 0px;
	font-family: inherit;
	font-size: 14px;
}
/* -----------------------Lead page Css----------------------------- */
	.lcont-lead-page .pac-container.pac-logo{
			z-index: 2147483647;
	}
	.lcont-lead-page .filter_select{
		margin-top: 16px;
	}
	.lcont-lead-page .filter_label{
		margin-top: 25px;
	}
	.lcont-lead-page .lead_address{
		background-color:#c1c1c1;
		padding: 10px 12px;
		margin-bottom: 17px;
		margin-top: 6px;
	}
	.lcont-lead-page .lead_view{
		background-color:#c1c1c1;
		padding: 10px 12px;
	}
	.lcont-lead-page #mapname,#edit_mapname{
		width: 100%;
		height: 150px;
		border: 1px;
		position: relative;
		overflow: hidden;
		margin-bottom: 12px;
	}
	.lcont-lead-page .btn_log{
		margin-bottom: 5px;
	}

	.lcont-lead-page .apport_label label{
		font-weight:bold!important;
	}

	.lcont-lead-page #tree_leadsource,
	.lcont-lead-page #tree_lead_source,
	.lcont-lead-page #leadsource_excel_upload{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}
	.lcont-lead-page #tree_leadsource1{
		position: absolute;
		background: white;
		z-index: 99;
		top: -50px;
		left: 100px;
		border: 1px solid #ccc;
		padding: 10px;
		border-radius: 5px;
	}


	.lcont-lead-page .multiselect2{
		height: 180px;
		overflow: auto;
		border: 1px solid #ccc;
		border-radius: 5px;
	}
	.lcont-lead-page .multiselect2 ul{
		padding: 0px;
	}
	.lcont-lead-page .multiselect2 ul li.sel{
		background: #ccc;
	}
	.lcont-lead-page .multiselect2 ul li{
		padding: 0 10px;
	}

	.lcont-lead-page #tablebody .tooltip.bottom .tooltip-arrow{
		olor:black;
	}
	.lcont-lead-page #tablebody .tooltip.bottom .tooltip-inner{
		background:black;
		color:white;
		text-align:left;
	}
	.lcont-lead-page .leadsrcname {
		border: 1px solid #ccc;
		width: 65.4%;
		padding: 6px;
		border-radius: 4px;
		height: 34px;
		float: right;
		margin-left: 0px;
	}
	.lcont-lead-page .custom-file-upload {
		border: 1px solid #ccc;
		display: inline-block;
		padding: 6px 12px;
		cursor: pointer;
		width: 100%;
		border-radius: 5px;
	}
	.lcont-lead-page .rejected_lead,
	.lcont-lead-page .legend{
		background-color: rgba(180, 0, 10, 0.20) !important;
	}

	.lcont-lead-page .legend{
		width: 30px;
		height: 30px;
		margin: -5px 10px 0px 0px;
	}
	.lcont-lead-page .legend-wrapper{
		width: 200px;
		margin:auto;
		float: none;
		margin-left:25px;
	}

	.lcont-lead-page .no_opacity_tooltip .tooltip.in .tooltip-inner{
		text-align:left;
	}
	.lcont-lead-page .no_opacity_tooltip .tooltip.in{
		opacity: 1;
		padding:5px;
		background:  #ccc;

	}
	.lcont-lead-page .star_rating{
		width:100px
	}
	.lcont-lead-page textarea.pre.form-control{
		min-height:150px;
		border: 1px solid #ccc;
		background: #fff;
		padding: 5px;
	}
	.lcont-lead-page ol#label_product li {
		display: inline-table;
		margin-right: 0px;
	}
	.modal .tt .tooltip{
		width:250px;
		opacity: initial;
	}
	.modal .tt .tooltip .tooltip-inner{
		max-width:none;
		background:#f5f5f5;
		text-align:left;
	}
	.modal .tt .tooltip .tooltip-arrow{
		color:black;
	}
	.tooltip.bottom .tooltip-inner {
		background-color:#333!important;
		color: white!important;
	}
	
	.cke_path{
		display:none;
	}
</style>

<script>
document.addEventListener('play', function(e){
    var audios = document.getElementsByTagName('audio');
    for(var i = 0, len = audios.length; i < len;i++){
        if(audios[i] != e.target){
            audios[i].pause();
        }
    }
}, true);
var obj_value = {};
obj_value = [
	{"link":"admin_rolesHierarchyController","content":"Once department and role is added, please enter  Roles Hierarchy.","link1":"admin_roleController", "page":"Roles Hierarchy", "navigation":"Function > Company > "},
	{"link":"admin_currencyController","content":"Once Roles Hierarchy is added, please enter Currency.","link1":"admin_rolesHierarchyController", "page":"Currency", "navigation":"Function > Commerce > "},
	{"link":"admin_product_hierarchyController","content":"Once Currency is added, please enter Product.","link1":"admin_currencyController", "page":"Product", "navigation":"Function > Commerce > "},
	{"link":"admin_industry_hierarchyController","content":"Once  Product is added, please enter Industry.","link1":"admin_product_hierarchyController", "page":"Industry", "navigation":"Function > Commerce > "},
	{"link":"admin_holidaysController","content":"Once Industry is added, please enter Holiday Calendar.","link1":"admin_industry_hierarchyController", "page":"Calendar", "navigation":"Function > Operations > "},
	{"link":"admin_countryStateController","content":"Once Holiday Calendar is added, please enter Country & State.","link1":"admin_holidaysController", "page":"Country & State", "navigation":"Function > Operations > "},
	{"link":"admin_office_location","content":"Once Country & State is added, please enter Office Location.","link1":"admin_countryStateController", "page":"Location", "navigation":"Function > Operations > Locations > "},
	{"link":"admin_blocation_hierarchyController","content":"Once Office Location is added, please enter Business Location.","link1":"admin_office_location", "page":"Business Location", "navigation":"Function > Operations > Locations > "},
	{"link":"admin_teamController","content":"Once Business Location is added, please enter Team.","link1":"admin_blocation_hierarchyController", "page":"Team", "navigation":"Function > Operations > "},
	{"link":"admin_userController1","content":"Once Team is added, please enter User.","link1":"admin_teamController", "page":"User", "navigation":"Function > "},
	{"link":"admin_mastersales_cycleController","content":"Once User is added, please enter Master Cycle.","link1":"admin_userController1", "page":"Master Cycle", "navigation":"Function > Sales > Master Cycle > "},
	{"link":"admin_sales_cycleController","content":"Once Master cycle is added, please enter Sales Cycle.","link1":"admin_mastersales_cycleController", "page":"Sales Cycle", "navigation":"Function > Sales > Sales Cycle > "},
	{"link":"admin_salescycle_parameterController","content":"Once Sales Cycle is added, please enter Sales Parameter.","link1":"admin_sales_cycleController", "page":"Sales Parameter", "navigation":"Function > Sales > Sales Cycle > "},
	{"link":"admin_sales_stageController","content":"Once Sales Parameter is added, please enter Sales Stage FlowChart ","link1":"admin_salescycle_parameterController", "page":"Stage FlowChart", "navigation":"Function > Sales > Sales Cycle > "},
	{"link":"admin_qualifiersController","content":"Once Sales Stage FlowChart is added, please enter Qualifiers.","link1":"admin_sales_stageController", "page":"Qualifiers", "navigation":"Function > Sales > Sales Cycle > "},
	{"link":"admin_sales_stage_flowchartController","content":"Once Qualifiers is added, please enter Stage Attribute.","link1":"admin_qualifiersController", "page":"Stage Attribute", "navigation":"Function > Sales > Sales Cycle > "}
];
/* ----------------------Blocking Copy pest----------------------------*/
	var isNS = (navigator.appName == "Netscape") ? 1 : 0;

	/*if(navigator.appName == "Netscape") document.captureEvents(Event.MOUSEDOWN||Event.MOUSEUP||Event.CLICK);

	function mischandler(){
		return false;
	}

	function mousehandler(e){
		var myevent = (isNS) ? e : event;
		var eventbutton = (isNS) ? myevent.which : myevent.button;
		if((eventbutton==2)||(eventbutton==3)) return false;
	}
	document.oncontextmenu = mischandler;
	document.onmousedown = mousehandler;
	document.onmouseup = mousehandler;
	var isCtrl = false;
	document.onkeyup=function(e){
		if(e.which == 17)
		isCtrl=false;
	}

	document.onkeydown=function(e){
		if(e.which == 17)
			isCtrl=true;
		if(((e.which == 85) || (e.which == 117) || (e.which == 65) || (e.which == 97) || (e.which == 67) || (e.which == 99) || (e.which == 83) || (e.which == 80) || (e.which == 86)) && isCtrl == true){
			return false;
		}
	}
	document.onmousedown=function(e){
		if(e.which == 17)
			isCtrl=true;
	    if (e.button==1 && isCtrl == true) {
	        return false;
	    }
	}*/

 /*-------------------------------------------------- */
function tableSearch(){
	$(".dataTables_filter input").each(function(){
		if($.trim($(this).val())  == ""){
			$(this).css({
							"background": "url('<?php echo base_url(); ?>images/Search-Engine-Optimization.jpg ')",
							'background-repeat': 'no-repeat',
							'background-size': 'contain'
						})
		}else{
			$(this).css({
							"background": "url('<?php echo base_url(); ?>images/giphy.gif')",
							'background-repeat': 'no-repeat',
							'background-size': 'contain'
						})
		}
	})
}

function comment_validation(name) {
	var nameReg = new RegExp(/^[a-zA-Z0-9 $&:()#@\n_.,+%?-]*$/);
	var valid = nameReg.test(name);
	if (!valid) {
		return false;
	} else {
		return true;
	}
}

/* Loading theam --Starts */
function loaderHide(){
	$(".loader").fadeOut(1400);
	/* $("#loader_txt").text("");
	$(".loader marquee").remove(); */
}
function loaderShow(){
	/* $(".loader").append('<marquee style="background:#ccc;margin-top:10%" direction="right"><img style="width:200px;" src="<?php echo base_url(); ?>images/new/White Logo.png"></marquee>');
	changeText("loader_txt",150); */
	$(".loader").fadeIn(1400);
}
/* function changeText(cont1,speed){
	var Otext="Loading.. Please wait for some time :)";
	var Ocontent=Otext.split("");
	var i=0;
	function show(){
		if(i<Ocontent.length){
			$("#"+cont1).append(Ocontent[i]);
			i=i+1;
		};
	};
	var Otimer=setInterval(show,speed);
}; */
/* Loading theam --Ends */

/*-----------------------------------
function validate_PhNo(value) {
	var nameReg = new RegExp(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/);
	var valid = nameReg.test(value);
	if (!valid) {
		return false;
	}else{
		return true;
	}
}
------------------------------------*/

/*Validation : Phone Number */
function validate_PhNo(value){
	value = value.trim()
	var nameReg = new RegExp(/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/);
	var nameReg3 = new RegExp(/^\(?([0-9+]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/);
	var nameReg2 = new RegExp(/^\(?([0-9+]{2})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/);
	var nameReg1 = new RegExp(/^\(?([0-9]{1})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/);
	var valid = nameReg.test(value);
	var valid1 = nameReg1.test(value);
	var valid2 = nameReg2.test(value);
	var valid3 = nameReg3.test(value);
	if (!valid && !valid1 && !valid2 && !valid3) {
		return false;
	}else{
		return true;
	}
}/*Validation : Phone Number */

/*Validation : No special character */
function validate_noSpCh(name) {
  var nameReg = new RegExp(/^[a-zA-Z0-9 ]*$/);
  var valid = nameReg.test(name);
  if (!valid) {
   return false;
  } else {
   return true;
  }
}/*Validation : special characters */

/*Validation : special characters */
function validate_location(name) {
  var nameReg = new RegExp(/^[a-zA-Z0-9 &]*$/);
  var valid = nameReg.test(name);
  if (!valid) {
   return false;
  } else {
   return true;
  }
}/*Validation : special characters */

function validate_name(name) {
		var nameReg = new RegExp(/^[a-zA-Z0-9 &_.-]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
}
function validate_email(name) {
		/* var nameReg = new RegExp(/^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/); */
		var nameReg = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
}
function validate_website(website) {
    var nameReg = new RegExp( /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/);
    var valid = nameReg.test(website);
    if (!valid) {
        return false;
    } else {
        return true;
    }
}
function validate_zip(name) {
        var nameReg = new RegExp(/^[a-zA-Z0-9 -]*$/);
        var valid = nameReg.test(name);
        if (!valid) {
                return false;
        } else {
                return true;
        }
}
function validate_leadname(name) {
        var nameReg = new RegExp(/^[a-zA-Z]*$/);
        var valid = nameReg.test(name);
        if (!valid) {
                return false;
        } else {
                return true;
        }
}
/*Validation : special characters it will accept all special character except single quote' and back shesh \*/
function valid_name(name) {
		var nameReg = new RegExp(/^[^'\\]*$/);
		var valid = nameReg.test(name);
		if (!valid) {
			return false;
		} else {
			return true;
		}
	}
/* Validation : first character digit */
function firstLetterChk(name) {
	var nameReg = new RegExp(/^[a-zA-Z]/);
	var valid = nameReg.test(name);
	if (!valid) {
		return false;
	} else {
		return true;
	}
}

function capitalizeFirstLetter(string) {
	    return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>
<script>
$(document).ready(function(){
		console.log(obj_value)
		var ua = window.navigator.userAgent.toLowerCase();
		var zoomlabel = .8;
		/* ---------IE 10 or older => return version number--------- */
		if (ua.indexOf('msie') > 0){
			$('#orgChart').css({
				"-webkit-transform": "scale(.8)" ,
				"transform": "scale(.8)"
			});
			$(".glyphicon.glyphicon-zoom-in").click(function(){
				if(zoomlabel < 1.2){
					zoomlabel = zoomlabel + .1;
					$('#orgChart').css({
						"-webkit-transform": "scale("+zoomlabel+")" ,
						"transform": "scale("+zoomlabel+")"
					});
				}
			});

			$(".glyphicon.glyphicon-zoom-out").click(function(){
				if(zoomlabel >.6){
					zoomlabel = zoomlabel - .1;
					$('#orgChart').css({
						"-webkit-transform": "scale("+zoomlabel+")" ,
						"transform": "scale("+zoomlabel+")"
					});
				}
			});
			return parseInt(ua.substring(msie + 5, ua.indexOf('.', msie)), 10);
		}

		/* ---------IE 11 => return version number--------- */
		if (ua.indexOf('trident/') > 0) {
			$('#orgChart').css({
				"-webkit-transform": "scale(.8)" ,
				"transform": "scale(.8)"
			});
			$(".glyphicon.glyphicon-zoom-in").click(function(){
				if(zoomlabel < 1.2){
					zoomlabel = zoomlabel + .1;
					$('#orgChart').css({
						"-webkit-transform": "scale("+zoomlabel+")" ,
						"transform": "scale("+zoomlabel+")"
					});
				}
			});

			$(".glyphicon.glyphicon-zoom-out").click(function(){
				if(zoomlabel >.6){
					zoomlabel = zoomlabel - .1;
					$('#orgChart').css({
						"-webkit-transform": "scale("+zoomlabel+")" ,
						"transform": "scale("+zoomlabel+")"
					});
				}
			});
			var rv = ua.indexOf('rv:');
			return parseInt(ua.substring(rv + 3, ua.indexOf('.', rv)), 10);
		}

		/* ------------Edge (IE 12+) => return version number-------------- */
		if (ua.indexOf('edge/') > 0) {
			/* return parseInt(ua.substring(edge + 5, ua.indexOf('.', edge)), 10); */
		}

		/* ------------------------if firefox------------------ */
		if(ua.indexOf('firefox') > -1){
			$('#orgChart').css({
				"-moz-transform": "scale(.8)",
				"transform": "scale(.8)"
			});
			$(".glyphicon.glyphicon-zoom-in").click(function(){
				if(zoomlabel < 1.2){
					zoomlabel = zoomlabel + .1;
					$('#orgChart').css({ 'transform': "scale("+zoomlabel+")"});
				}
			});

			$(".glyphicon.glyphicon-zoom-out").click(function(){
				if(zoomlabel >.6){
					zoomlabel = zoomlabel - .1;
					$('#orgChart').css({ 'transform': "scale("+zoomlabel+")"});
				}
			});
		}
		/* --------------if chrome or if safari------------------ */
		if(ua.indexOf('chrome') > -1 || ua.indexOf('safari/') > -1){
			$('#orgChart').css('zoom','.8');
			$(".glyphicon.glyphicon-zoom-in").click(function(){
				if(zoomlabel < 1.4){
					zoomlabel = zoomlabel + .2;
					$('#orgChart').animate({ 'zoom': zoomlabel }, 400);
				}
			});
			$(".glyphicon.glyphicon-zoom-out").click(function(){
				if(zoomlabel >.6){
					zoomlabel = zoomlabel - .2;
					$('#orgChart').animate({ 'zoom': zoomlabel }, 400);
				}
			});
		}
		/* -------------------------------- */


	})
	$( window ).load(function() {
		$('form').on('keyup keypress', function(e) {
		  var keyCode = e.keyCode || e.which;
		  if (keyCode === 13) {
			$("form").submit(function(e){
				e.preventDefault();
			});
		  }
		});

		var url1= window.location.href;
		var fileNameIndex1 = url1.lastIndexOf("/") + 1;
		var filename1 = url1.substr(fileNameIndex1);
		$("#pre_url").val(url1);
		$(".sidebar-menu a").each(function(){
			var url = $(this).attr("href");
			$(this).closest("li").removeClass("active")
			if(url !=undefined){
				var fileNameIndex = url.lastIndexOf("/") + 1;
				var filename = url.substr(fileNameIndex);
				filename1 = filename1.replace("#","");
				if(filename1 == filename){
					
						for(j = 0; j <obj_value.length; j++){	
							if(filename == obj_value[j].link1){
								$(".info-icon div img").attr("title" , "Click to get Navigation Hint");
							}
						}
						$(".info-icon div").bind( "click", function() {
							var row = "", row1 = "", domain = '<?php echo site_url(); ?>';
							for(i=0; i<obj_value.length; i++){
								if(filename == obj_value[i].link1){
									row1 += "<a href= '"+ domain + obj_value[i].link +"'> <u>"+obj_value[i].page+"</u></a>";
									$("#modal_text").empty();
									$("#modal_text").append(obj_value[i].content + "<br/> > " +obj_value[i].navigation);
									$("#modal_text").append(row1);
									$("#myModal").modal("show");									
								}
							}
						});
						
					/* $(this).css("background","transparent"); */
					$(this).closest("li").addClass("active");
					$(this).closest("li").closest("ul").closest("li").addClass("active");
					$(this).closest("li").closest("ul").closest("li").closest("ul").closest("li.treeview ").addClass("active sdds");
					$(this).closest("li").closest("ul").closest("li").closest("ul").addClass("menu-open");
					$(this).closest("li").closest("ul").closest("li").closest("ul").closest("li").addClass("active");
					
				}else if(filename1 == "admin_mastersales_stageController" || filename1 == "admin_mastersales_cycleController"){
					/* master cycle 2 pages marged */
					$("li.MasterCycleNav").addClass("active");
					$("li.MasterCycleNav").closest("ul").closest("li").addClass("active");
					$("li.MasterCycleNav").closest("ul").closest("li").closest("ul").closest("li.treeview ").addClass("active sdds");
					$("li.MasterCycleNav").closest("ul").closest("li").closest("ul").addClass("menu-open");
					$("li.MasterCycleNav").closest("ul").closest("li").closest("ul").closest("li").addClass("active");
					
				}else if(filename1 == "admin_sales_cycleController" || filename1 == "admin_sales_stageController"){
					/* sales cycle 2 pages marged */
					$("li.SalesCycleNav").addClass("active");
					$("li.SalesCycleNav").closest("ul").closest("li").addClass("active");
					$("li.SalesCycleNav").closest("ul").closest("li").closest("ul").closest("li.treeview ").addClass("active sdds");
					$("li.SalesCycleNav").closest("ul").closest("li").closest("ul").addClass("menu-open");
					$("li.SalesCycleNav").closest("ul").closest("li").closest("ul").closest("li").addClass("active");
					
				}else if(filename1 == "admin_sup_mastersales_cycleController" || filename1 == "admin_sup_mastersales_stageController"){
					/* sales cycle 2 pages marged */
					$("li.MasterSupportCycle").addClass("active");
					$("li.MasterSupportCycle").closest("ul").closest("li").addClass("active");
					$("li.MasterSupportCycle").closest("ul").closest("li").closest("ul").closest("li.treeview ").addClass("active sdds");
					$("li.MasterSupportCycle").closest("ul").closest("li").closest("ul").addClass("menu-open");
					$("li.MasterSupportCycle").closest("ul").closest("li").closest("ul").closest("li").addClass("active");
				}else if(filename1 == "admin_sup_sales_cycleController" || filename1 == "admin_sup_sales_stageController"){
					/* sales cycle 2 pages marged */
					$("li.SupportCycleNav").addClass("active");
					$("li.SupportCycleNav").closest("ul").closest("li").addClass("active");
					$("li.SupportCycleNav").closest("ul").closest("li").closest("ul").closest("li.treeview ").addClass("active sdds");
					$("li.SupportCycleNav").closest("ul").closest("li").closest("ul").addClass("menu-open");
					$("li.SupportCycleNav").closest("ul").closest("li").closest("ul").closest("li").addClass("active");
				}
			}
		});
	});
	/* -------------------------------------------------------- */
	/*
		Multiple contact person fetch:
		Manager lead and customer page
		Executive lead and customer page
	*/
	/* -------------------------------------------------------- */
	function contact_prsn_list(lead_id , container, page_type, module){
		var obj={}, url_type;
		if(module == "manager"){
			if(page_type == "customer"){
				url_type = "<?php echo site_url('manager_customerController/contactsForLeadCust');?>";
				obj.customer_id=lead_id;
			}else{
				url_type = "<?php echo site_url('manager_leadController/contactsForLeadCust');?>";
				obj.leadid=lead_id;
			}
		}else if(module == "sales"){
			if(page_type == "customer"){
				url_type = "<?php echo site_url('sales_customerController/contactsForLeadCust');?>";
				obj.customer_id=lead_id;
			}else{
				url_type = "<?php echo site_url('leadinfo_controller/contactsForLeadCust');?>";
				obj.leadid=lead_id;
			}
		}
		$.ajax({
			type: "POST",
			url: url_type,
			data : JSON.stringify(obj),
			dataType:'json',
			success: function(tableData) {
				console.log(tableData)
				$('#'+container).html("");
				for(i=0; i < tableData.length; i++ ){
					var site_url = "<?php echo base_url(''); ?>" ;
					if (!tableData[i].contact_photo) {
						site_url = site_url + "images/default-pic.jpg"
					} else {
						site_url = site_url + "uploads/" + tableData[i].contact_photo;
					}
					contact_name = capitalizeFirstLetter(tableData[i].contact_name);
					lead_name = capitalizeFirstLetter(tableData[i].lead_cust_name);
					contact_for = capitalizeFirstLetter(tableData[i].contact_for);
					contact_desg = capitalizeFirstLetter(tableData[i].contact_desg);
					contact_desg = capitalizeFirstLetter(tableData[i].contact_desg);
					contact_type_name = capitalizeFirstLetter(tableData[i].contact_type_name);
					employeephone1 = tableData[i].employeephone1;
					employeephone2 = tableData[i].employeephone2;
					employeeemail = tableData[i].employeeemail;
					employeeemail2 = tableData[i].employeeemail2;
					contact_address = tableData[i].contact_address;
					contact_id = tableData[i].contact_id;
					var active = "";
					if(i == 0){
						active = 'in';
					}
					row ='<div class="panel-group">'+
							'<div class="panel panel-default">'+
								'<div class="panel-heading">'+
									'<a data-toggle="collapse" data-parent="#accordion" href="#'+contact_id+'">'+
										'<h4 class="panel-title">'+contact_name+'</h4>'+
									'</a>'+
								'</div>'+
								'<div id="'+contact_id+'" class="panel-collapse collapse '+active+'">'+
									'<div class="panel-body">'+
										"<div class='row'>"+
											"<div class='col-md-2'>"+
												"<img class='img-thumbnail' width='100' height='100' title='" +contact_name+ "' alt='" +contact_name+ "' src='"+site_url+"' />";
												
											row +=	"</div>"+
											"<div class='col-md-5'>"+
												"<div class='row'>"+
													"<div class='col-md-12'><b><i class='fa fa-user'></i></b> "+ contact_name +"</div>";
												row +="</div><div class='row'>";
												if(employeeemail != ""){
													row +="<div class='col-md-5'><b><i class='fa fa-at'></i></b> " + employeeemail +"</div>";
												}
												row +="</div><div class='row'>";
												if(employeephone1 != ""){
													row +="<div class='col-md-12'><b><i class='fa fa-phone-square'></i></b> "+ employeephone1 +"</div>";
												}
												row +="</div><div class='row'>";
												if(contact_desg != ""){
													row +="<div class='col-md-5'><b>Designation</b>:</div><div class='col-md-7'>" + contact_desg +"</div>";
												}
												row +="</div><div class='row'>";
												if(contact_type_name != "" && contact_type_name != "-"){
													row +="<div class='col-md-5'><b>Contact Type</b>:</div><div class='col-md-7'>" + contact_type_name + "</div>";
												}
												row +="</div><div class='row'>";
												if(employeephone2 != ""){
													row +="<div class='col-md-5'><b>Secondary Number</b>:</div><div class='col-md-7'>" + employeephone2 + "</div>";
												}
												row +="</div><div class='row'>";
												if(employeeemail2 != ""){
													row +="<div class='col-md-5'><b>Secondary Email</b>:</div><div class='col-md-7'>" + employeeemail2 + "</div>";
												}
												row +="</div>";
											row += "</div>";											
											if(contact_address != "" && contact_address != null){
												row +=	"<div class='col-md-5'>"+
															"<div class='row'><div class='col-md-3'>"+
																"<b>Address:</b>"+
															"</div>"+
															"<div class='col-md-9 no-padding' style='margin-left:-8px;'>"+
																"<textarea disabled class='pre form-control'>"+contact_address+"</textarea>"+
															"</div></div>"+
														"</div>";
											}
									'</div>'+
									'</div>'+
								'</div>'+
							'</div>'+
						'</div>';
					$('#'+container).append(row);
				}
			},
			error:function(data){
				network_err_alert();
			}
		});
	}

	function leadinfoView(obj,module,container){
		$("#"+container).html("");
		if(obj.logo !="" && obj.logo !=null){
			src = "<?php echo site_url()?>uploads/"+obj.logo;
		}else{
			src =  "<?php echo site_url()?>uploads/default-pic.jpg";
		}

		if(obj.email != "" && obj.email != null){
			email = '<b title="'+ module +' Email ID"><i class="fa fa-at"></i></b> '+obj.email;
		}else{
			email = '';
		}

		if(obj.phone != "" && obj.phone != null){
			phone = '<b title="'+ module +' Phone Number"><i class="fa fa-phone-square"></i></b> '+obj.phone;
		}else{
			phone = '';
		}


	var html=	'<div class="row">';
		html+=	'<div class="col-md-6 no-padding">'+
					'<div class="row">'+
						'<div class="col-md-4 no-padding" align="center">'+
							'<img class="img-thumbnail" width="150" height="100" alt="'+ module +' Logo" title="'+ module +' Logo"  src="'+ src +'"/>'+
						'</div>'+
						'<div class="col-md-8">'+
							'<div class="row">'+
								'<label id="leadname_label">'+
									'<b title="'+ module +' Name"><i class="fa fa-user"></i></b> '+obj.name+
								'</label><br/>'+
								'<label>'+ email +'</label> <br/>'+
								'<label>'+ phone +'</label>'+
							'</div>';
							if(obj.website != "" && obj.website != null){
								html+=	'<div class="row">'+
											'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
												'<label>'+ module +' Website</label>'+
											'</div>'+
											'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
												'<label>'+ obj.website +'</label>'+
											'</div>'+
										'</div>';
							};
							if(obj.hasOwnProperty('source') && obj.source != "" && obj.source != null && obj.source != '-'){
								html+=	'<div class="row">'+
											'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
												'<label>'+ module +' Source</label>'+
											'</div>'+
											'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
												'<label>'+ obj.source +'</label>'+
											'</div>'+
										'</div>';
							}
							if(obj.country != "" && obj.country != null && obj.country != '-'){
								html+=	'<div class="row">'+
											'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label" >'+
												'<label>Country</label>'+
											'</div>'+
											'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
											   '<label>'+ obj.country +'</label>'+
											'</div>'+
										'</div>';
							};

		
		html+=	'</div></div></div>';
		html+=	'<div class="col-md-6 no-padding">';
		if(obj.state != "" && obj.state != null){
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label>State</label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							'<label>'+ obj.state +'</label>'+
						'</div>'+
					'</div>';
		};
		if(obj.city != "" && obj.city != null){
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label>City</label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							'<label>'+ obj.city +'</label>'+
						'</div>'+
					'</div>';
		}
		if(obj.zip != "" && obj.zip != null){
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label>Zipcode</label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							 '<label>'+ obj.zip +'</label>'+
						'</div>'+
					'</div>';
		}
		if(obj.industry != "" && obj.industry != null){
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label>Industry</label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							 '<label>'+ obj.industry +'</label>'+
						'</div>'+
					'</div>';
		}
		if(obj.Blocation != "" && obj.Blocation != null){
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label for="view_location">Business Location</label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							 '<label>'+ obj.Blocation+'</label>'+
						'</div>'+
					'</div>';
		}
		if(obj.hasOwnProperty('product') && obj.product.length > 0){
			var li = ""
			for(var j=0;j<obj.product.length; j++){
				if(j == (obj.product.length - 1)){
					li+='<li>'+obj.product[j].hvalue2+'</li>';
				}else{
					li+='<li>'+obj.product[j].hvalue2+'</li>, ';
				}
			}
			html+=	'<div class="row">'+
						'<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 apport_label">'+
							'<label for="view_product"><b>Product</b></label>'+
						'</div>'+
						'<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8">'+
							'<ol id="label_product" style="padding-left:0px">'+li+'</ol>'+
						'</div>'+
					'</div>';
		}
		html+='</div>';
		html+='</div>';

		$("#"+container).html(html);
	}
</script>
<!--------------------------------------------------------------------video----------------------------------------------------------------->
<script>
	function select_video(value, container, module){
		var val = '', module_name = '';
		if(module == 'Manager'){
			module_name = 'https://lconnectt.in/L-Connectt_Videos/'+module+'/'+value+'.mp4';
		}else if(module == 'Executive'){
			module_name = 'https://lconnectt.in/L-Connectt_Videos/'+module+'/'+value+'.mp4';
		}else if(module == 'Admin'){
			module_name = 'https://lconnectt.in/L-Connectt_Videos/'+module+'/'+value+'.mp4';
		}
		val = '<div class="modal_video none animate">'+
		'<div class="video_header">'+
			'<span style="font-size:24px;">Video Guide</span>'+
			'<span class="video_cls_button" id="'+container+'" onclick="cancel_close(this.id)">&times;</span>'+
		'</div>'+
		'<div class="modal-body video_play">'+
			'<video autoplay width="100%" controls controlsList="nodownload">'+
				'<source src="'+module_name+'" type="video/mp4">'+
				'Your browser does not support the video tag.'+
			'</video> '+
		'</div>'+
		'</div>'

		$("." + container).empty().append(val);
		$(".modal_video").draggable().resizable().css("display", "block");
		//$(".modal_video").css("display", "block");
	}
	function cancel_close(container){
		$(".modal_video").fadeOut();
		setTimeout( function(){$("." + container).empty();}, 500);

	}
	/*audio hide*/
	$(document).ready(function(){		
		$('button, input[type=button], .nav-tabs').on('click', function(event) {
			$('audio').each(function(){
				this.pause(); // Stop playing
				this.currentTime = 0; // Reset time
			});
			//alert(event.target.id);
		});
	});
</script>
<!--Information i modal-->
<div class="modal fade" id="myModal" data-backdrop="false">
	<div class="modal-dialog">				
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-body">
				<p id="modal_text"></p>
				<div align="right">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>	  
	</div>
</div>
<!--task completed confirmation modal-->
<div class="modal fade" id="completed_Modal" data-backdrop="false">
	<div class="modal-dialog">				
	  <!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<h4>Alert</h4>
			</div>
			<div class="modal-body">
				<p>The activity you are trying to complete is from an earlier date. By clicking continue, you will be rescheduling this activity and creating a new activity for the current date. Please click OK to continue, else click CANCEL to manually reschedule.</p>
			</div>
			<div class="modal-footer">
				<input type="button" class="btn" onclick="complete_alert()" value="OK" />
				<input type="button" class="btn" onclick="cancel_completed()" value="Cancel" />
			</div>
		</div>	  
	</div>
</div>