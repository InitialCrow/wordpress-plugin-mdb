(function($,ctx){
  var app = {
    init : function(){
      $(document).ready(function(){
        app.addHour(".addHour")
        app.closeHour(".close-btn")
      })
    },
    closeHour : function(classN){
      $(document).on('click', classN, function(evt){
        $this = $(this)
        $.ajax({
          type: "POST",
          data : {"delete":$this.attr('data-id')},
          url: postHandling.timesUrl,
          dataType: "JSON",
          success: function(p1,p2){

            $this.parent().remove()
          },
          error : function(result, status, error){
                console.log(error)
          }

        });

      })
    },
    addHour:function(classN){
      $inputs = $(classN)
      $inputs.on('keyup',function(evt){
        if(evt.keyCode !== 13)
          return;

        var $val = $(this).val()
        var arr = $val.split('-')
        var openH = arr[0][0]+arr[0][1]
        var closeH = arr[1][0]+arr[1][1]
        var openM ="00"
        var closeM = "00"
        if(arr[0].length > 2 ){
          openM = arr[0][2]+arr[0][3]
        }
        if(arr[1].length > 2){
          closeM = arr[1][2]+arr[1][3]
        }
        var formated = {
          index : $(this).parent().parent().index()-1,
          openH : openH,
          closeH : closeH,
          openM : openM,
          closeM : closeM
        }


        var $self = $(this)
        $.ajax({
          type: "POST",
          url: postHandling.timesUrl,
          data:{"hours":formated},
          dataType: "JSON",
          success: function(p1,p2){
            var data = p1
            var h =""
            if(data.openH!=undefined ){
              h+=data.openH+"h"+data.openM
            }
            if(data.closeH !=undefined){
              h+="-"+data.closeH+"h"+data.closeM
            }
            var html = "<div class='hours'><div class='close-btn' data-id=\""+data.insertId+"\"></div>"+h+"</div>"
            var parent = $self.parent().parent()
            parent.append(html)
          },
          error : function(result, status, error){
                  console.log(result)
                console.log(error)
          }

        });
      })
    }
  }
  ctx.mdb_plugin = app
})(jQuery,window)