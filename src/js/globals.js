var mapSquareSize = 500;
var mapSize = 50;
var map = {offset_x:0, offset_y:0};
var userCities = null;
var otherCities = null;
var currentCity = 0;
var currentCityId = 0;
var commingAttacks = goingAttacks = null;
var lone = [0, 0, 0, 0, 0];
var listReports = null;
var attackAlerted = 1;
var selectedCity = null;
var currentTasks = null;
var window_focus;


var distanceRate = 12;//43;
var spmRate = 5.3;
