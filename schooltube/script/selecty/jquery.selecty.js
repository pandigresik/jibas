;(function($, document) {

var initialized = false;

$.fn.selecty = function(options) {

	var self = this;
	self.options = $.extend({}, {
		animationSpeed: 200,
		width: '300px',
		maxHeight: '200px'
	}, options);

	this.each(function(id, el) {
		var $el = $(el);
		var thisOptions = $.extend({}, self.options, $el.data());
		el.style.display = 'none';

		var $template = $('\
			<div class="selecty-container" style="width: ' + thisOptions.width + '"> \
				<div class="selecty-select"> \
					<div class="selecty-arrow">▼</div> \
					<div class="selecty-content">' + $el.find(':selected').text() + '</div> \
				</div> \
				<div class="selecty-items-container" style="width: ' + thisOptions.width + '"> \
					<ul class="selecty-items" style="width: ' + thisOptions.width + '; max-height: ' + thisOptions.maxHeight + '"> \
					</ul> \
				</div> \
			</div>');

		var $items = $template.find('.selecty-items');

		$el.find('>*').each(function() {
			if ($(this).is('optgroup')) {
				$(this).each(function() {
					var $_this = $(this);
					$items.append('<li class="section">' + $_this.attr('label') + '</li>');
					$_this.find('option').each(function() {
						var $this = $(this);
						var disabledStr = $this.prop('disabled') ? 'disabled' : '';
						$items.append('<li class="inside-section" ' + disabledStr + ' data-value="' + $this.attr('value') + '">' + $this.text() + '</li>');
					});
				});
			} else {
				var $this = $(this);
				var disabledStr = $this.prop('disabled') ? 'disabled' : '';
				$items.append('<li ' + disabledStr + ' data-value="' + $this.attr('value') + '">' + $this.text() + '</li>');
			}
		});


		$template.insertAfter(el);
		$items.css('margin-top', -$items.outerHeight());
	});

	// Init

	if (!initialized) {

		initialized = true;

		$(document).on('click.selecty', function(e) {
			if ($(e.target).closest('.selecty-container').length == 0) {
				$('.selecty-select.open').trigger('click');
			}
		});

		$(document).on('click.selecty', '.selecty-select', function() {
			var $container = $(this).closest('.selecty-container');
			var $sic = $container.find('.selecty-items-container');
			var $si = $sic.find('.selecty-items');

			$('.selecty-select.open').not(this).trigger('click');
			$(this).toggleClass('open');

			if ($(this).hasClass('open')) {
				$sic.animate({height: $si.outerHeight(), opacity: 1}, {queue: false, duration: self.options.animationSpeed});
				$si
                    .addClass('frozen')
                    .animate({marginTop: 0}, {
                        queue: false,
                        duration: self.options.animationSpeed,
                        always: function() {
                            $si.removeClass('frozen');
                        }
                    });
			} else {
				$sic.animate({height: 0, opacity: .8}, {queue: false, duration: self.options.animationSpeed});
				$si
                    .addClass('frozen')
                    .animate({marginTop: -$si.outerHeight()}, {
                        queue: false,
                        duration: self.options.animationSpeed,
                        always: function() {
                            $si.removeClass('frozen');
                        }
                    });
			}
		});

		$(document).on('click.selecty', '.selecty-items > li[data-value]:not([disabled])', function() {
			$(this).closest('.selecty-container')
				.find('.selecty-select')
					.find('>.selecty-content')
						.text($(this).text())
						.end()
					.trigger('click')
					.end()
				.prev('select')
					.val($(this).data('value'))
					.trigger('change');
		});
	}

	return this;
};

})(jQuery, document);