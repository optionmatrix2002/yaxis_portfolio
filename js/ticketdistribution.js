/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

AmCharts.addInitHandler(function(chart) {

    // check if data is mepty
    if (chart.dataProvider === undefined || chart.dataProvider.length === 0) {
        // add some bogus data


        var dp = {};
        dp[chart.titleField] = "";
        dp[chart.valueField] = 0;
        chart.dataProvider.push(dp)

        // disable slice labels
       // chart.labelsEnabled = false;

        // add label to let users know the chart is empty
        chart.balloonText = "";
        chart.addLabel("50%", "50%", "No tickets available", "middle", 15);

        // dim the whole chart
       // chart.alpha = 0.3;
    }

}, ["pie"]);

console.log(ticketLocData)
var samplepiechart  = [
    {name: "Scheduled Tasks", assigned_user_id: "342", ticket_count: "336"},
    {name: "Overdue Tasks", assigned_user_id: "342", ticket_count: "55"},
    {name: "Active Tasks", assigned_user_id: "342", ticket_count: "545"},
    {name: "Pending Tasks", assigned_user_id: "342", ticket_count: "320"},
    {name: "Chronic Issues", assigned_user_id: "342", ticket_count: "85"},
    {name: "Completed Tasks", assigned_user_id: "342", ticket_count: "200"}
];
var chart = AmCharts.makeChart("taskChat", {
    "type": "pie",
    "theme": "light",
    "labelRadius": -25,
    "labelText": "[[value]]",
    "legend": {
        "markerType": "circle",
        "position": "right",
        "valueText": '',
        "font-size": "10"
    },
    "dataProvider": samplepiechart,
    "valueField": "ticket_count",
    "titleField": "name",
    "colorField": "color",
    "colors" :['#8aa830','#4677af','#F08080','#FF6600','#FF3333','#00FF00'],
    "labelColorField": "fcolor",
    "depth3D": 8,
});
var samplepiechart2  = [
    {name: "Scheduled Audit", assigned_user_id: "342", ticket_count: "100"},
    {name: "Overdue Audit", assigned_user_id: "342", ticket_count: "205"},
    {name: "Active Audit", assigned_user_id: "342", ticket_count: "55"},
    {name: "Pending Audit", assigned_user_id: "342", ticket_count: "120"},
    {name: "Chronic Audit", assigned_user_id: "342", ticket_count: "185"},
    {name: "Completed Audit", assigned_user_id: "342", ticket_count: "400"}
];
var chart = AmCharts.makeChart("taskChat2", {
    "type": "pie",
    "theme": "light",
    "labelRadius": -25,
    "labelText": "[[value]]",
    "legend": {
        "markerType": "circle",
        "position": "right",
        "valueText": '',
        "font-size": "10"
    },
    "dataProvider": samplepiechart2,
    "valueField": "ticket_count",
    "titleField": "name",
    "colorField": "color",
    "colors" :['#8aa830','#4677af','#F08080','#FF6600','#FF3333','#00FF00'],
    "labelColorField": "fcolor",
    "depth3D": 8,
});
var chart = AmCharts.makeChart("chartdivpie1", {
    "type": "pie",
    "theme": "light",
    "labelRadius": -25,
    "labelText": "[[value]]",
    "legend": {
        "markerType": "circle",
        "position": "right",
        "valueText": '',
        "font-size": "10"
    },
    "dataProvider": ticketLocData,
    "valueField": "ticket_count",
    "titleField": "name",
    "colorField": "color",
    "colors" :['#8aa830','#4677af','#F08080','#FFFACD','#FFD700','#FFFFE0'],
    "labelColorField": "fcolor",
    "depth3D": 8,
});
var chart = AmCharts.makeChart("chartdivpie2", {
    "type": "pie",
    "labelRadius": -25,
    "labelText": "[[value]]",
    "legend": {
        "markerType": "circle",
        "position": "right",
        "valueText": '',
        "font-size": "10"
    },
    "dataProvider": ticketHotelData,
    "valueField": "ticket_count",
    "titleField": "hotel_name",
    "colorField": "color",
    "colors" :['#8aa830','#4677af','#F08080','#FFFACD','#FFD700','#FFFFE0'],
    "labelColorField": "fcolor",
    "depth3D": 8,
});
var chart = AmCharts.makeChart("chartdivpie3", {
    "type": "pie",
    "labelRadius": -25,
    "labelText": "[[value]]",
    "legend": {
        "markerType": "circle",
        "position": "right",
        "valueText": '',
        "font-size": "10"
    },
    "dataProvider": ticketDeptData,
    "valueField": "ticket_count",
    "titleField": "department_name",
    "colorField": "color",
    "colors" :['#8aa830','#4677af','#F08080','#FFFACD','#FFD700','#FFFFE0'],
    "labelColorField": "fcolor",
    "depth3D": 8,
});


