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
    
    $("#costbenefititem-turnover-monthly-value,#costbenefititem-turnover-yearly-value").keyup(function(){
        updateExpenses();
        updateProfit();
    });
    
    $("#costbenefititem-salaries-monthly-value,#costbenefititem-salaries-yearly-value").keyup(function(){
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
        var currentValue = $(field).val();
        var yearlyValue = currentValue * 12;
        
        var yearlyId = currentId.replace("monthly","yearly");
        $('#'+yearlyId).val(yearlyValue);
    };
    
    updateSalaries = function(){
        var employees = $("#company-employees option:selected").val();
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];

        var avgWage = industrySetup['avgWage'];
        
        var salaries = avgWage*employees;
        $("#costbenefititem-salaries-monthly-value").val(salaries);
        $("#costbenefititem-salaries-yearly-value").val(salaries*12);
    };
    
    updateSideExpenses = function(){
        var salaries = $("#costbenefititem-monthly-salaries-value").val();
        var expenses = Math.round( salaries * 0.3 );
        
        $("#costbenefititem-sideexpenses-monthly-value").val(expenses);
        $("#costbenefititem-sideexpenses-yearly-value").val(expenses*12);
    };
    
    updateTurnover = function(){
        var industryId =  $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        
        var avgWage = industrySetup['avgWage'];
        var employees = $("#company-employees option:selected").val();
        var salaries = avgWage*(employees-1)*11;
        
        var turnover = parseInt(industrySetup['turnover']) + parseInt(salaries);
        
        $("#costbenefititem-turnover-monthly-value").val(turnover);
        $("#costbenefititem-turnover-yearly-value").val(turnover*12);
    };
    
    updateExpenses = function(){
        var turnover = $("#costbenefititem-turnover-monthly-value").val();

        var expenses = Math.round( (turnover * 0.8) );
        
        $("#costbenefititem-expenses-monthly-value").val(expenses);
        $("#costbenefititem-expenses-yearly-value").val(expenses*12);
    };
    
    updateLoans = function(){
        var expenses = parseInt($("#costbenefititem-expenses-monthly-value").val());
        var salaries = parseInt($("#costbenefititem-salaries-monthly-value").val());
        var sideExpenses = parseInt($("#costbenefititem-sideexpenses-monthly-value").val());
        var rents = parseInt($("#costbenefititem-rents-monthly-value").val());
        var communication = parseInt($("#costbenefititem-communication-monthly-value").val());

        // Calculate loan sum. 3x all expenses + one months expenses
        loanSum = (expenses+salaries+sideExpenses+rents+communication)*3 + expenses;
        var interest = 3.3 / 100 / 12;
        payment = loanSum * ( interest / (1 - Math.pow((1+interest), -60)));
        
        loans = Math.round(payment);
        
        $("#costbenefititem-loans-monthly-value").val(loans);
        $("#costbenefititem-loans-yearly-value").val(loans*12);
    };
    
    updateRents = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var rents = parseInt(industrySetup['rents']) * (1+Math.floor((parseInt(employees)/5)));
        
        $("#costbenefititem-rents-monthly-value").val(rents);
        $("#costbenefititem-rents-yearly-value").val(rents*12);       
    };
    
    updateCommunication = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var communication = Math.round(parseInt(industrySetup['communication'])*(1+(parseInt(employees)-1)*0.2));
        
        $("#costbenefititem-communication-monthly-value").val(communication);
        $("#costbenefititem-communication-yearly-value").val(communication*12); 
    };
    
    updateHealth = function(){
    	// TODO
        $("#costbenefititem-health-monthly-value").val(0);
        $("#costbenefititem-health-yearly-value").val(0);
    };
    
    updateOtherExpenses = function(){
    	// TODO
        $("#costbenefititem-otherexpenses-monthly-value").val(0);
        $("#costbenefititem-otherexpenses-yearly-value").val(0);
    };
    
    updateProfit = function(){
        var turnover = Number($("#costbenefititem-turnover-monthly-value").val());
        var expenses = Number($("#costbenefititem-expenses-monthly-value").val());
        var salaries = Number($("#costbenefititem-salaries-monthly-value").val());
        var side = Number($("#costbenefititem-sideexpenses-monthly-value").val());
        var loans = Number($("#costbenefititem-loans-monthly-value").val());
        var rents = Number($("#costbenefititem-rents-monthly-value").val());
        var communication = Number($("#costbenefititem-communication-monthly-value").val());
        var health = Number($("#costbenefititem-health-monthly-value").val());
        var other = Number($("#costbenefititem-otherexpenses-monthly-value").val());

        var profit = Math.round(turnover-expenses-salaries-side-loans-rents-communication-health-other);
        
        $("#costbenefititem-profit-monthly-value").val(profit);
        $("#costbenefititem-profit-yearly-value").val(profit*12);
    };
    
    fillYearlyFields = function(){
        $("#costBenefitCalculationTable input").each(function() {
            if($(this).parent().parent().attr('id').indexOf("monthly") >= 0){
               updateYearlyField($(this));
            }
        });
    };
    
    //updateProfit();
    //fillYearlyFields();
});