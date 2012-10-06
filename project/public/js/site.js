(function($) {
  // code block formatter
  prettyPrint();

  // reply comment box
  $('ul.comments .reply a').click(function() {
    var $a = $(this);
    var $article = $a.closest('article');
    var $comment = $article.closest('li');
    var $childComment = $('ul.comments', $comment);

    $('li.add-comment').remove();

    if (!$childComment.length) {
      $comment.append('<ul class="comments"></ul>');

      $childComment = $('ul.comments', $comment);
    }

    $childComment.prepend('<li class="add-comment"></li>');
    var $addCommentLi = $('li', $childComment).first();

    $addCommentLi.load($a.attr('href'), function() {
      $('li.add-comment textarea').focus();
    });

    return false;
  });

  // vote
  $('.voting a').click(function() {
    var $a = $(this);
    var $points = $('.points', $a.parent());

    $.getJSON($a.attr('href'), function(data) {
      if (data.points === false) {
        return;
      }

      $points.html(data.points);
    });

    return false;
  });
})(jQuery);