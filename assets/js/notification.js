// getNotification();

var a = 0;
setInterval(function(){
    a+= 1;

    var current = new Date();
    var hour = current.getHours();
    if(hour < 10){
        hour = "0"+hour;
    }
    var minute = current.getMinutes();
    if(minute < 10){
        minute = "0"+minute;
    }
    var second = current.getSeconds();
    if(second < 10){
        second = "0"+second;
    }
    var fullTime = hour+":"+minute+":"+second;
    console.log(hour+":"+minute+":"+second);
    console.log(a);

    var notificationAction = "get";
    var requestData = {};
    if(fullTime == "00:00:00" || (a > 0 && a % 10 == 0)){
        notificationAction = "send";
    }
    requestData = {
        action: notificationAction
    };

    if(tenant_id != null){
        requestData = {
            action: notificationAction,
            tenant_id: tenant_id
        };
    }

    getNotification(requestData);
},1000);

function getNotification(requestData){
    $.ajax({
        method : "POST",
        url : fullUrl,
        async: false,
        data : JSON.stringify(requestData),
        success : function(resultData){
            // console.log(resultData);
            var data = resultData['data'];
            var totalUnreadNotifications = resultData['total_unread_messages'];

            console.log(resultData);
            
            if(requestData['action'] != "send" && requestData['action'] != "read"){
                var notificationDiv = "";
                notificationDiv += '<h6 class="dropdown-header mykosan-alert-header">';
                notificationDiv += 'Notifications Center';
                notificationDiv += '</h6>';
                if(data != null){
                    for(b = 0; b < data.length; b++){
                        var dateObj = new Date(data[b]['created_date']);
                        // var weekday = dateObj.toLocaleString("default", { weekday: "long" });
                        // var date =
                        var monthList = ["January","February","March","April","May","June","July","August","September","October","November","December"];
                        var year = dateObj.getFullYear();
                        var date = dateObj.getDate();
                        if(date < 10){
                            date = ("0"+date);
                        }
                        var month = dateObj.getMonth();
                        var fullDate = monthList[month]+' '+date+', '+year;
                        // console.log(weekday);
    
                        if(tenant_id == null){
                            notificationDiv += '<a class="dropdown-item d-flex align-items-center" href="#">';
                            notificationDiv += '<div class="mr-3">';
                            notificationDiv += '<div class="icon-circle bg-primary">';
                            notificationDiv += '<i class="fas fa-file-alt text-white"></i>';
                            notificationDiv += '</div>';
                            notificationDiv += '</div>';
                            notificationDiv += '<div>';
                            notificationDiv += '<div class="small text-gray-500">'+fullDate+'</div>';
                            notificationDiv += '<span class="font-weight-bold">'+data[b]['description'].replace('[user]',(data[b]['first_name']+' '+data[b]['last_name'])).replace('[invoice_code]',data[b]['invoice_number']).replace('[transaction_code]',data[b]['transaction_code'])+'</span>';
                            notificationDiv += '</div>';
                            notificationDiv += '</a>';
                        }else{
                            notificationDiv += '<li>'
                            notificationDiv += '<a class="dropdown-item d-flex align-items-center">'
                            notificationDiv += '<div class="mr-3">'
                            notificationDiv += '<div class="icon-circle bg-primary mykosan-icon-background-color" style="height: 2.5rem;width: 2.5rem;border-radius: 100%;display: flex;align-items: center;justify-content: center;">'
                            notificationDiv += '<i class="fas fa-file-alt text-white"></i>'
                            notificationDiv += '</div>'
                            notificationDiv += '</div>'
                            notificationDiv += '<div style="color: #b7b9cc !important;font-size: 80%;font-weight: 400;">'
                            notificationDiv += '<span>'+data[b]['description'].replace('[user]',(data[b]['first_name']+' '+data[b]['last_name'])).replace('[invoice_code]',data[b]['invoice_number']).replace('[transaction_code]',data[b]['transaction_code'])+'</span>'
                            notificationDiv += '<span class="d-block" style="font-size:11px;">'+fullDate+'</span>'
                            notificationDiv += '</div>'
                            notificationDiv += '</a>'
                            notificationDiv += '</li>'
                        }
                    }
                }else{
                    if(tenant_id == null){
                        notificationDiv += '<a class="dropdown-item d-flex align-items-center" href="#">';
                        notificationDiv += '<div class="mr-3">';
                        notificationDiv += '<div class="icon-circle bg-primary">';
                        notificationDiv += '<i class="fas fa-file-alt text-white"></i>';
                        notificationDiv += '</div>';
                        notificationDiv += '</div>';
                        notificationDiv += '<div>';
                        notificationDiv += '<div class="small text-gray-500">No data found!</div>';
                        notificationDiv += '</div>';
                        notificationDiv += '</a>';
                    }else{
                        notificationDiv += '<li>'
                        notificationDiv += '<a class="dropdown-item d-flex align-items-center">'
                        notificationDiv += '<div class="mr-3">'
                        notificationDiv += '<div class="icon-circle bg-primary mykosan-icon-background-color" style="height: 2.5rem;width: 2.5rem;border-radius: 100%;display: flex;align-items: center;justify-content: center;">'
                        notificationDiv += '<i class="fas fa-file-alt text-white"></i>'
                        notificationDiv += '</div>'
                        notificationDiv += '</div>'
                        notificationDiv += '<div style="color: #b7b9cc !important;font-size: 80%;font-weight: 400;">'
                        notificationDiv += '<span>No data found!</span>'
                        notificationDiv += '</div>'
                        notificationDiv += '</a>'
                        notificationDiv += '</li>'
                    }
                }
    
                document.getElementById('notificationDropdown').innerHTML = notificationDiv;
    
                if(typeof totalUnreadNotifications !== 'undefined'){
                    totalUnreadNotifications = totalUnreadNotifications;
                }else{
                    totalUnreadNotifications = total_unread_notification;
                }
                
    
                document.getElementById('totalUnreadNotifications').innerHTML = (totalUnreadNotifications > 10 ? "10+" : totalUnreadNotifications);
            }
        }
        // error: function(){
        //     console.log("bbb");
        // }
    });
}

document.getElementById('alertsDropdown').onclick = function(){
    // console.log("aaaaa");
    requestData = {
        action: "read"
    };

    getNotification(requestData);
};