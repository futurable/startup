$(document).ready(function(){
    
    // Replace enter with tab
    $('input').keydown( function(e) {
        var key = e.charCode ? e.charCode : e.keyCode ? e.keyCode : 0;
        if(key == 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq( inputs.index(this)+ 1 ).focus();
        }
    });
            
    updateAllFields = function(){
        updateSalaries();
        updateSideExpenses();
        updateRents();
        updateCommunication();
        updateTurnover();
        updateExpenses();
        updateLoans();
        updateHealth();
        updateOtherExpenses();
        updateProfit();
    };
    
    $("#company-industry_id,#company-employees").change(updateAllFields);
    
    $("#costbenefititem-monthly-turnover-value,#costbenefititem-yearly-turnover-value").keyup(function(){
        updateExpenses();
        updateProfit();
    });
    
    $("#costbenefititem-monthly-salaries-value,#costbenefititem-yearly-salaries-value").keyup(function(){
        updateSideExpenses();
    });
    
    $("#costBenefitCalculationTable input").keyup(function(){;
        updateCalculationFields($(this));
        updateProfit();
    });
    
    /**
     * Update cost-benefit calculation fields
     * from monthly to yearly and vice versa
     * 
     * @param {object} field
     */
    updateCalculationFields = function( field ){
        var currentId = field.attr('id');
        if(currentId.indexOf("monthly") >= 0){
        	updateYearlyField(field);
        }
        else if(currentId.indexOf("yearly") >= 0){
        	updateMonthlyField(field);
        }
    };
    
    /**
     * Update cost-benefit calculation monthly field
     * using yearly value
     * 
     * @param {object} field
     */
    updateMonthlyField = function( field ){
    	var currentId = field.attr('id');
        var currentValue = $(field).val();
        
        var monthlyValue = Math.round(currentValue/12*100) / 100;
       
        var monthlyId = currentId.replace("yearly","monthly");
        $('#'+monthlyId).val(monthlyValue);
    };
    
     /**
     * Update cost-benefit calculation yearly field
     * using monthly value
     * 
     * @param {object} field
     */
    updateYearlyField = function( field ){
    	var currentId = field.attr('id');
        var currentValue = $('#' + currentId + ' input').val();
        var yearlyValue = currentValue * 12;
        
        var yearlyId = currentId.replace("monthly","yearly");
        $('#' + yearlyId + ' input').val(yearlyValue);
    };
    
    updateSalaries = function(){
        var employees = $("#company-employees option:selected").val();
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];

        var avgWage = industrySetup['avgWage'];
        
        var salaries = avgWage*employees;
        $("#CostbenefitItem_salaries_monthly input").val(salaries);
        $("#CostbenefitItem_salaries_yearly input").val(salaries*12);
    };
    
    updateSideExpenses = function(){
        var salaries = $("#CostbenefitItem_salaries_monthly input").val();
        var expenses = Math.round( salaries * 0.3 );
        
        $("#CostbenefitItem_sideExpenses_monthly input").val(expenses);
        $("#CostbenefitItem_sideExpenses_yearly input").val(expenses*12);
    };
    
    updateTurnover = function(){
        var industryId =  $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        
        var avgWage = industrySetup['avgWage'];
        var employees = $("#company-employees option:selected").text();
        var salaries = avgWage*(employees-1)*11;
        
        var turnover = parseInt(industrySetup['turnover']) + parseInt(salaries);
        
        $("#CostbenefitItem_turnover_monthly input").val(turnover);
        $("#CostbenefitItem_turnover_yearly input").val(turnover*12);
    };
    
    updateExpenses = function(){
        var turnover = $("#CostbenefitItem_turnover_monthly input").val();

        var expenses = Math.round( (turnover * 0.8) );
        
        $("#CostbenefitItem_expenses_monthly input").val(expenses);
        $("#CostbenefitItem_expenses_yearly input").val(expenses*12);
    };
    
    updateLoans = function(){
        var expenses = parseInt($("#CostbenefitItem_expenses_monthly input").val());
        var salaries = parseInt($("#CostbenefitItem_salaries_monthly input").val());
        var sideExpenses = parseInt($("#CostbenefitItem_sideExpenses_monthly input").val());
        var rents = parseInt($("#CostbenefitItem_rents_monthly input").val());
        var communication = parseInt($("#CostbenefitItem_communication_monthly input").val());

        // Calculate loan sum. 3x all expenses + one months expenses
        loanSum = (expenses+salaries+sideExpenses+rents+communication)*3 + expenses;
        var interest = 3.3 / 100 / 12;
        payment = loanSum * ( interest / (1 - Math.pow((1+interest), -60)));
        
        loans = Math.round(payment);
        
        $("#CostbenefitItem_loans_monthly input").val(loans);
        $("#CostbenefitItem_loans_yearly input").val(loans*12);
    };
    
    updateRents = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var rents = parseInt(industrySetup['rents']) * (1+Math.floor((parseInt(employees)/5)));
        
        $("#CostbenefitItem_rents_monthly input").val(rents);
        $("#CostbenefitItem_rents_yearly input").val(rents*12);       
    };
    
    updateCommunication = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var communication = Math.round(parseInt(industrySetup['communication'])*(1+(parseInt(employees)-1)*0.2));
        
        $("#CostbenefitItem_communication_monthly input").val(communication);
        $("#CostbenefitItem_communication_yearly input").val(communication*12); 
    };
    
    updateHealth = function(){
    	// TODO
        $("#CostbenefitItem_health_monthly input").val(0);
        $("#CostbenefitItem_health_yearly input").val(0);
    };
    
    updateOtherExpenses = function(){
    	// TODO
        $("#CostbenefitItem_otherExpenses_monthly input").val(0);
        $("#CostbenefitItem_otherExpenses_yearly input").val(0);
    };
    
    updateProfit = function(){
        var turnover = Number($("#CostbenefitItem_turnover_monthly input").val());
        var expenses = Number($("#CostbenefitItem_expenses_monthly input").val());
        var salaries = Number($("#CostbenefitItem_salaries_monthly input").val());
        var side = Number($("#CostbenefitItem_sideExpenses_monthly input").val());
        var loans = Number($("#CostbenefitItem_loans_monthly input").val());
        var rents = Number($("#CostbenefitItem_rents_monthly input").val());
        var communication = Number($("#CostbenefitItem_communication_monthly input").val());
        var health = Number($("#CostbenefitItem_health_monthly input").val());
        var other = Number($("#CostbenefitItem_otherExpenses_monthly input").val());
        
        var profit = Math.round(turnover-expenses-salaries-side-loans-rents-communication-health-other);
        if(!$.isNumeric(profit)) profit = 0;
        
        $("#CostbenefitItem_profit_monthly input").val(profit);
        $("#CostbenefitItem_profit_yearly input").val(profit*12);
    };
    
    fillYearlyFields = function(){
        $("#costBenefitCalculationTable input").each(function() {
            if($(this).parent().parent().attr('id').indexOf("monthly") >= 0){
               updateYearlyField($(this));
            }
        });
    };
    
    updateProfit();
    fillYearlyFields();
});