var was_set = 0;
function checkForAttacks(){
    ajaxPost('/ajax/game/get_attacks', {
        city_id: currentCityId
    }, function(data){
        commingAttacks = data.ingoing;
        goingAttacks = data.outgoing;
        if(typeof data.ingoing[0] != 'undefined'){
            if(!was_set){
                was_set = 1;
                attackAlerted = 0;
            }
            alertForAttack();
        }else{
            attackAlerted = 1;
            was_set = 0;
            $(".cityimage.attack").removeClass('attack');
        }
        drawAttacks();
    });
}
function alertForAttack(){
    if(attackAlerted == 0){
        attackAlerted = 1;
        $(".menuh.mme .cityimage").addClass('attack');
        error_txt('Click on the top left logo to see when will the attack get your city.', 'You\'re under attack!');
    }

}

$(document).on('click', '.menuh.mme .cityimage', function(e){
    e.preventDefault();
    $("img.my-city[data-id=city-"+currentCityId+"]").click();
})
