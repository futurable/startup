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
        $("#costbenefititem-monthly-salaries-value").val(salaries);
        $("#costbenefititem-yearly-salaries-value").val(salaries*12);
    };
    
    updateSideExpenses = function(){
        var salaries = $("#costbenefititem-monthly-salaries-value").val();
        var expenses = Math.round( salaries * 0.3 );
        
        $("#costbenefititem-monthly-sideexpenses-value").val(expenses);
        $("#costbenefititem-yearly-sideexpenses-value").val(expenses*12);
    };
    
    updateTurnover = function(){
        var industryId =  $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        
        var avgWage = industrySetup['avgWage'];
        var employees = $("#company-employees option:selected").text();
        var salaries = avgWage*(employees-1)*11;
        
        var turnover = parseInt(industrySetup['turnover']) + parseInt(salaries);
        
        $("#costbenefititem-monthly-turnover-value").val(turnover);
        $("#costbenefititem-yearly-turnover-value").val(turnover*12);
    };
    
    updateExpenses = function(){
        var turnover = $("#costbenefititem-monthly-turnover-value").val();

        var expenses = Math.round( (turnover * 0.8) );
        
        $("#costbenefititem-monthly-expenses-value").val(expenses);
        $("#costbenefititem-yearly-expenses-value").val(expenses*12);
    };
    
    updateLoans = function(){
        var expenses = parseInt($("#costbenefititem-monthly-expenses-value").val());
        var salaries = parseInt($("#costbenefititem-monthly-salaries-value").val());
        var sideExpenses = parseInt($("#costbenefititem-monthly-sideexpenses-value").val());
        var rents = parseInt($("#costbenefititem-monthly-rents-value").val());
        var communication = parseInt($("#costbenefititem-monthly-communication-value").val());

        // Calculate loan sum. 3x all expenses + one months expenses
        loanSum = (expenses+salaries+sideExpenses+rents+communication)*3 + expenses;
        var interest = 3.3 / 100 / 12;
        payment = loanSum * ( interest / (1 - Math.pow((1+interest), -60)));
        
        loans = Math.round(payment);
        
        $("#costbenefititem-monthly-loans-value").val(loans);
        $("#costbenefititem-yearly-loans-value").val(loans*12);
    };
    
    updateRents = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var rents = parseInt(industrySetup['rents']) * (1+Math.floor((parseInt(employees)/5)));
        
        $("#costbenefititem-monthly-rents-value").val(rents);
        $("#costbenefititem-yearly-rents-value").val(rents*12);       
    };
    
    updateCommunication = function(){
        var industryId = $("#company-industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#company-employees option:selected").val();
        
        var communication = Math.round(parseInt(industrySetup['communication'])*(1+(parseInt(employees)-1)*0.2));
        
        $("#costbenefititem-monthly-communication-value").val(communication);
        $("#costbenefititem-yearly-communication-value").val(communication*12); 
    };
    
    updateHealth = function(){
    	// TODO
        $("#costbenefititem-monthly-health-value").val(0);
        $("#costbenefititem-yearly-health-value").val(0);
    };
    
    updateOtherExpenses = function(){
    	// TODO
        $("#costbenefititem-monthly-otherexpenses-value").val(0);
        $("#costbenefititem-yearly-otherexpenses-value").val(0);
    };
    
    updateProfit = function(){
        var turnover = Number($("#costbenefititem-monthly-turnover-value").val());
        var expenses = Number($("#costbenefititem-monthly-expenses-value").val());
        var salaries = Number($("#costbenefititem-monthly-salaries-value").val());
        var side = Number($("#costbenefititem-monthly-sideexpenses-value").val());
        var loans = Number($("#costbenefititem-monthly-loans-value").val());
        var rents = Number($("#costbenefititem-monthly-rents-value").val());
        var communication = Number($("#costbenefititem-monthly-communication-value").val());
        var health = Number($("#costbenefititem-monthly-health-value").val());
        var other = Number($("#costbenefititem-monthly-otherexpenses-value").val());

        var profit = Math.round(turnover-expenses-salaries-side-loans-rents-communication-health-other);
        
        $("#costbenefititem-monthly-profit-value").val(profit);
        $("#costbenefititem-yearly-profit-value").val(profit*12);
    };
    
    fillYearlyFields = function(){
        $("#costBenefitCalculationTable input").each(function() {
            if($(this).parent().parent().attr('id').indexOf("monthly") >= 0){
               updateYearlyField($(this));
            }
        });
    };
    
    updateProfit();
    //fillYearlyFields();
});