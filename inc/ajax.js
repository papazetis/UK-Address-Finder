jQuery(document).ready(function( $ ) {

  var map

  $('.uaf_postcode').on('keyup', function () {
    event.preventDefault();
    var uaf_postcode = $('.uaf_postcode').val(),
        wpnonce = $('#uaf_wpnonce').val()


    $.ajax({
      url: ajax_uaf.ajaxurl,
      method : 'POST',
      data: {
        action: 'ajax_postcode',
        security: wpnonce,
        uaf_postcode: uaf_postcode,
      },
      success: function(data) {
        $("#result").html(data)
        var response_200 = $('#response_200').val(),
            latitude = $('#latitude').val(),
            longitude = $('#longitude').val()
        if (response_200 == 200) {
          $('#gmap').css('display', 'block')
          map = new GMaps({
            el: '#gmap',
            zoom: 16,
            lat: latitude,
            lng: longitude
          });
        } else {
          $('#gmap').css('display', 'none')
        }
      },

      complete: function() {
        $('#address_sel').on('change', function () {
          event.preventDefault()
          var address_sel = $('#address_sel').val()

          $.ajax({
            url: ajax_uaf.ajaxurl,
            method : 'POST',
            data: {
              action: 'ajax_gmap',
              address_sel: address_sel
            },
            success: function(data) {
              // $("#gmap").html(data)
              GMaps.geocode({
                address: address_sel,
                callback: function(results, status) {
                  if (status == 'OK') {
                    var latlng = results[0].geometry.location;
                    map.setZoom(17);
                    map.setCenter(latlng.lat(), latlng.lng());
                    map.removeMarkers();
                    map.addMarker({
                      lat: latlng.lat(),
                      lng: latlng.lng()
                    });
                  }
                }
              });
            }
          })
        })
      }
    })
  })
})
