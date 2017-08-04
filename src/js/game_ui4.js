var reports_first_loaded = 0;
function getReports(){
    ajaxPost('/ajax/game/get_reports', {
        city_id: currentCityId
    }, function(data){
        if(reports_first_loaded == 0){
            reports_first_loaded = 1;
            listReports = data;
            addReports(1);
        }else{
            if(JSON.stringify(data) != JSON.stringify(listReports)){
                listReports = data;
                info_txt('You\'ve got a new report. Click on the top left icon of your city to read it.', 'New notification!');
                addReports();
            }
        }
    });
}
var last_rep_read = 0;
function addReports(init){
    if(typeof init != 'undefined' && init){
        init = true;
    }else init = false;
    var html1;

    if(!listReports || listReports.length == 0){
        $(".report-list .accordion-data").html('<p>Sorry, no reports to show!</p>')
    }else $(".report-list .accordion-data").html('');
    if(listReports[0].id != last_rep_read){
        last_rep_read = listReports[0].id;
        for(var i in listReports){
            v = listReports[i];
            html1 = '<div>';
            html1 += '<h4>'+v.title+'</h4>';
            html1 += '<p>'+v.content+'</p>';
            html1 += '</div>';
            if(init){
                $(".report-list .accordion-data").append(html1);
                if(v.id > last_rep_read) last_rep_read = v.id;
            }else{
                if(v.id > last_rep_read){
                    last_rep_read = v.id;
                    $(".report-list .accordion-data").prepend(html1);
                }
            }
        }
    }
}
