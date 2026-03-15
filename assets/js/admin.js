/**
 * NeonMagic Admin JavaScript
 *
 * @package NeonMagic
 */

(function ($) {
  'use strict';

  var galleryFrame;
  var $galleryWrap  = $('#nm-gallery-wrap');
  var $galleryInput = $('#nm_gallery');
  var $galleryAdd   = $('#nm-gallery-add');

  function updateGalleryInput() {
    var ids = [];
    $galleryWrap.find('[data-id]').each(function () {
      ids.push($(this).data('id'));
    });
    $galleryInput.val(ids.join(','));
  }

  $galleryAdd.on('click', function (e) {
    e.preventDefault();

    if (galleryFrame) {
      galleryFrame.open();
      return;
    }

    galleryFrame = wp.media({
      title    : 'Select Preview Images',
      multiple : true,
      library  : { type: 'image' },
      button   : { text: 'Add Images' },
    });

    galleryFrame.on('select', function () {
      var selection = galleryFrame.state().get('selection');
      selection.each(function (attachment) {
        var id    = attachment.id;
        var thumb = attachment.attributes.sizes
          ? (attachment.attributes.sizes.thumbnail || attachment.attributes.sizes.full).url
          : attachment.attributes.url;

        if ($galleryWrap.find('[data-id="' + id + '"]').length) return;

        var $item = $('<div class="nm-gallery-item" data-id="' + id + '">' +
          '<img src="' + thumb + '" alt="" />' +
          '<span class="nm-gallery-remove" data-id="' + id + '" title="Remove">&#x2715;</span>' +
          '</div>');

        $galleryWrap.append($item);
      });
      updateGalleryInput();
    });

    galleryFrame.open();
  });

  $galleryWrap.on('click', '.nm-gallery-remove', function () {
    $(this).closest('.nm-gallery-item').remove();
    updateGalleryInput();
  });

  var $changelogList = $('#nm-changelog-list');
  var $changelogAdd  = $('#nm-changelog-add');
  var changelogTpl   = $('#nm-changelog-template').html();

  $changelogAdd.on('click', function (e) {
    e.preventDefault();
    $changelogList.append(changelogTpl);
  });

  $changelogList.on('click', '.nm-remove-entry', function () {
    $(this).closest('.nm-changelog-entry').remove();
  });

  var $reqList = $('#nm-req-list');
  var $reqAdd  = $('#nm-req-add');

  var reqRowTpl = [
    '<div class="nm-req-row">',
    '  <select name="nm_req_type[]" style="width:90px;flex-shrink:0;border:2px solid #000;padding:4px;">',
    '    <option value="check">✓ Check</option>',
    '    <option value="info">ℹ Info</option>',
    '    <option value="cross">✗ Cross</option>',
    '  </select>',
    '  <input type="text" name="nm_req_text[]" placeholder="Requirement text..." />',
    '  <button type="button" class="nm-req-remove" title="Remove">&#x2715;</button>',
    '</div>',
  ].join('');

  $reqAdd.on('click', function (e) {
    e.preventDefault();
    $reqList.append(reqRowTpl);
  });

  $reqList.on('click', '.nm-req-remove', function () {
    $(this).closest('.nm-req-row').remove();
  });

  $('form#post').on('submit', function () {
    var lines = [];
    $reqList.find('.nm-req-row').each(function () {
      var type = $(this).find('select[name="nm_req_type[]"]').val();
      var text = $(this).find('input[name="nm_req_text[]"]').val().trim();
      if (text) {
        lines.push(type + '|' + text);
      }
    });
    $('#nm_requirements').val(lines.join('\n'));
  });

})(jQuery);
