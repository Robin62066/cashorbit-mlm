$(document).ready(function () {
  $('.ajax-delete').click(function (e) {
    e.preventDefault();
    var me = $(this);
    if (confirm("Are you sure to delete")) {
      $.get(appUrl, {
        m: 'delete',
        id: $(this).data("id"),
        table: $(this).data("table")
      }, function (result) {
        if (result.status == true) {
          me.parents('tr').hide();
        }
      }, 'json');
    }
  });
});

async function api_call(m, ob) {
  let url = apiUrl + '?m=' + m;
  let result = await axios.post(url, ob);
  let resp = result.data;
  return resp;
}
$(document).ready(function () {
  $('form').submit(function () {
    $(this).find('.btn-submit').html("Please wait...").prop('disabled', 'disabled');
  })

  $(".has-submenu > a").click(function (e) {
    e.preventDefault();
    $(this).parent().children("ul").slideToggle("slow");
  });
  $('.data-table').DataTable({
    "order": [],
    "pageLength": 50
  });

  $('.datepicker').datepicker({
    format: 'yyyy-mm-dd'
  });
  $('a.delete, .btn-delete').click(function () {
    if (!confirm('Are you sure to delete?'))
      return false;
  });

  $('.btn-confirm').click(function () {
    var msg = $(this).data('msg');
    if (!confirm(msg))
      return false;
  });
  $('.select2').select2();
  $(".btn-copy").click(function () {
    var metxt = $(this).data('copy');
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val(metxt).select();
    document.execCommand("copy");
    $temp.remove();
    $(this).html('<i class="fa fa-copy"></i> COPIED');
  });
})
