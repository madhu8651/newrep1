<!--
<script src="https://cdn.ckeditor.com/4.8.0/standard-all/ckeditor.js"></script>
 AdminLTE App -->
<div class="video_body"></div>

<script src="<?php echo base_url(); ?>dist/js/app.min.js"></script>
<script>
$(document).ready(function(){
	CKEDITOR.disableAutoInline = true;
	CKEDITOR.replace( 'editor1_common', {
		height: 240,
		toolbar: [
			{ name: 'insert', items: ['Font', 'FontSize', 'Image','Table'] },
					{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Undo', 'Redo','-', 'Outdent', 'Indent', '-', 'Blockquote','-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
					{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Strike', '-', 'Subscript', 'Superscript','RemoveFormat' ] },
					{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
					{ name: 'links', items: [ 'Link', 'Unlink' ] },
					{ name: 'tools', items: [ 'Maximize'] },
					{ name: 'document', items: [ 'Source'] },
		],
		image2_alignClasses: [ 'image-align-left', 'image-align-center', 'image-align-right' ],
		image2_disableResizer: true
	} );
})

</script>


