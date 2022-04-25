//#region ADVERTISEMENTS.PHP
var $radioFeed = $('input[type="radio"][name="adtype"][id="feedAdList"]');
var $radioReptile = $('input[type="radio"][name="adtype"][id="reptileAdList"]');
if (window.location.href.indexOf("checked=1") > -1) {
     $radioFeed.prop("checked", true);
     toggleOptionsOnAdvertisementsPHP();
}
else if (window.location.href.indexOf("checked=2") > -1) {
     $radioReptile.prop("checked", true);
     toggleOptionsOnAdvertisementsPHP();
}


function toggleOptionsOnAdvertisementsPHP() {
     if (document.getElementById('feedAdList').checked) {
          document.getElementById('allReptileAds').style.display = 'none';
          document.getElementById('allFeedAds').style.display = '';
          document.querySelectorAll('.uncheck').forEach(c => c.checked = 0);
          $("#reptileCompareButton").hide();
          $("#feedCompareButton").show();
     } else if (document.getElementById('reptileAdList').checked) {
          document.getElementById('allFeedAds').style.display = 'none';
          document.getElementById('allReptileAds').style.display = '';
          document.querySelectorAll('.uncheck').forEach(c => c.checked = 0);
          $("#feedCompareButton").hide();
          $("#reptileCompareButton").show();
     } else {
          document.getElementById('allReptileAds').style.display = 'none';
          document.getElementById('allFeedAds').style.display = 'none';
          document.querySelectorAll('.uncheck').forEach(c => c.checked = 0);
          $("#reptileCompareButton").hide();
          $("#feedCompareButton").hide();
     }
}

function validateMyForm() {

     var check = $("input:checked").length;
     if (check > 3) {
          alert("Maximum két hirdetést hasonlíthatsz össze!");
          return false;
     }
     else if (check < 3) {
          alert("Legalább két hirdetést ki kell jelölnöd az összehasonlításhoz!");
          return false;
     }
     return true;
}

$(document).ready(function () {
     $(document).on('click', '#feed_btn_more', function () {
          var post_id = $(this).data("id");
          $('#feed_btn_more').html("Betöltés...");
          $.ajax({
               url: "feed_advertisements_loader.php",
               method: "POST",
               data: { post_id: post_id },
               dataType: "text",
               success: function (data) {
                    if (data != '') {
                         $('#feed_remove_row').remove();
                         $('#feed_load_data_table').append(data);
                    }
                    else {
                         $('#feed_btn_more').html("Nincs több hirdetés");
                    }
               }
          });
     });

     $(document).on('click', '#reptile_btn_more', function () {
          var post_id = $(this).data("id");
          $('#reptile_btn_more').html("Betöltés...");
          $.ajax({
               url: "reptile_advertisements_loader.php",
               method: "POST",
               data: { post_id: post_id },
               dataType: "text",
               success: function (data) {
                    if (data != '') {
                         $('#reptile_remove_row').remove();
                         $('#reptile_load_data_table').append(data);
                    }
                    else {
                         $('#reptile_btn_more').html("Nincs több hirdetés");
                    }
               }
          });
     });
});
//#endregion

//#region NEWAD.PHP
function toggleOptionsOnNewAdPHP() {
     if (document.getElementById('feed').checked) {
          document.getElementById('reptileAdForm').style.display = 'none';
          document.getElementById('feedAdForm').style.display = '';
     } else if (document.getElementById('reptile').checked) {
          document.getElementById('feedAdForm').style.display = 'none';
          document.getElementById('reptileAdForm').style.display = '';
     } else {
          document.getElementById('reptileAdForm').style.display = 'none';
          document.getElementById('feedAdForm').style.display = 'none';
     }
}
//#endregion