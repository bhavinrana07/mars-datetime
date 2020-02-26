const API_URL = "http://localhost/symfony4_rest/public/api/";
const FIELDS = "fields";

initDateSend();

function initDateSend() {
  $("#send").click(function() {
    date = $("#form_datetime #date").val();
    time = $("#form_datetime #time").val();
    postFields(date, time);
  });
}
function postFields(date, time) {
  var timestamp = getTimeStamp(date, time);
  $.ajax({
    url: API_URL + "getMarsTime",
    type: "post",
    dataType: "json",
    contentType: "application/json",
    success: function(data) {
      showMarsDateTime(data);
    },
    data: JSON.stringify({ timestamp: timestamp })
  });
}
function getTimeStamp(date, time) {
  var timeParts = time.split(":"),
    dateParts = date.split("-"),
    date;

  date = new Date(
    dateParts[0],
    parseInt(dateParts[1], 10) - 1,
    dateParts[2],
    timeParts[0],
    timeParts[1]
  );
  return date.getTime() / 1000;
}
function showMarsDateTime(data) {
  $("#show_time").html(data.martian_coordinated_time);
  $("#show_date").html(data.mars_sol_date);
}

$(".datepicker").datepicker({
  format: "yyyy-mm-dd",
  todayHighlight: true,
  autoclose: true
});
