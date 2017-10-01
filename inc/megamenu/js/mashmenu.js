;(function($){
$(document).ready(function(e) {
	$('.sub-menu-box.preview-mode').each(function(index, element) {
		var channel_content = '';
		var channel_count = 0;
        $(this).find('.channel-content').each(function(index, element) {
			if(channel_count == 0){
				$(this).addClass('active');
			}
            channel_content += $(this)[0].outerHTML;
			$(this).remove();
			channel_count++;
        });
		$(this).append(channel_content);
    });
    
    $('.dropdown-mega').hover(function(){
        // find the first element to add active state
        titles = $('.sub-menu-box .channel-title',$(this));
        if(titles.length > 0){
            var target = "#" + $(titles[0]).attr("data-target");
			$(".channel-content").removeClass("active");
			$(target).addClass("active");
        }
    });
    
    $('.sub-menu-box .channel-title').hover(
		function(){
			var target = "#" + $(this).attr("data-target");
			$(".channel-content").removeClass("active");
			$(target).addClass("active");
		},
		function(){}
	)
});

}(jQuery));