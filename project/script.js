// Функции.
(function($) {
	$.fn.initGraphPointsListInspector = function() {
		return this.each(function() {
			var $this = $(this);

			var $x_body = $this.find('.points-list-x');
			var $y_body = $this.find('.points-list-y');
			
			// Блокировка текущего пункта.
			$('.points-list-y [value="' + $x_body.val() + '"]').attr('disabled', 'disabled');
			$('.points-list-y [value="' + (parseInt($x_body.val()) + 1) + '"]').attr('selected', 'selected');

			// Блокировка нового пункта.
			$x_body.on('change', function() {
				// Снятие блокировки с остальных пунктов.
				$this.find('.points-list-y [value]').removeAttr('disabled');

				// Блокировка нового.
				$this.find('.points-list-y [value="' + $x_body.val() + '"]').attr('disabled', 'disabled');
			});
		});
	};
}) (jQuery);

var redraw, g, renderer;

// Запуск сценариев после полной прогрузки страницы.
$(document).ready(function() {
	// Создание графа.
	var width = $('#canvas').width();
	var height = $('#canvas').height();

	var createGraph = function() {
		g = new Graph();

		$.each(relations, function(index, value) {
			if (value.fill) g.addEdge(value.start, value.end, {
				label: value.cost,
				directed: true,
				stroke: '#5cb85c',
				fill: '#5cb85c'
			});
			else g.addEdge(value.start, value.end, {
				label: value.cost,
				directed: true,
				stroke: '#bbb'
			});
		});

		/* Layout the graph using the Spring layout implementation. */
		var layouter = new Graph.Layout.Spring(g);
		
		/* Draw the graph using the RaphaelJS draw implementation. */
		renderer = new Graph.Renderer.Raphael('canvas', g, width, height);
	};


	if ($.isEmptyObject(relations) === false) createGraph();
	else $('#canvas').parent().text('Необходимо задать опорные точки и указать рёбра будушего графа.');
});
