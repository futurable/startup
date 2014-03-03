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
        updateOther();
        updateProfit();
    };
    
    $("#company-industry_id,#company-employees").change(updateAllFields);
    
    $("#CostbenefitItem_turnover_monthly").keyup(function(){
        updateExpenses();
    })
    
    $("#CostbenefitItem_salaries_monthly").keyup(function(){
        updateSideExpenses();
    })
    
    $("#costBenefitCalculationTable input").keyup(function(){
        updateCalculationFields($(this));
        updateProfit(this);
    });
    
    /**
     * Update cost-benefit calculation fields
     * from monthly to yearly and vice versa
     * 
     * @param {object} field
     */
    updateCalculationFields = function( field ){
        var currentId = field.attr('id');
        
        if(currentId.substring(0,15) === "CostbenefitItem"){
            updateYearlyField(field);
        }
        else if(currentId.substring(0,1) === "_"){
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
        var currentValue = field.attr('value');
        var currentId = field.attr('id');
        var monthlyValue = Math.round(currentValue/12*100) / 100;
        
        var monthlyId = "#CostbenefitItem"+currentId.replace("yearly","")+"_value";
        $(monthlyId).val(monthlyValue);
    };
    
     /**
     * Update cost-benefit calculation yearly field
     * using monthly value
     * 
     * @param {object} field
     */
    updateYearlyField = function( field ){
        var currentValue = field.attr('value');
        var currentId = field.attr('id');
        var yearlyValue = currentValue * 12;
        
        var yearlyId = "#_"+currentId.split('_')[1]+"yearly";
        $(yearlyId).val(yearlyValue);
    };
    
    updateSalaries = function(){
        var employees = $("#Company_employees option:selected").text();
        var industryId = $("#Company_industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];

        var avgWage = industrySetup['avgWage'];
        
        var salaries = avgWage*employees;
        $("#CostbenefitItem_salaries_value").val(salaries);
        $("#_salariesyearly").val(salaries*12);
    }
    
    updateSideExpenses = function(){
        var salaries = $("#CostbenefitItem_salaries_monthly input").val();
        var expenses = Math.round( salaries * 0.3 );
        
        $("#CostbenefitItem_sideExpenses_monthly input").val(expenses);
        $("#CostbenefitItem_sideExpenses_yearly input").val(expenses*12);
    }
    
    updateTurnover = function(){
        var industryId = $("#Company_industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        
        var avgWage = industrySetup['avgWage'];
        var employees = $("#Company_employees option:selected").text();
        var salaries = avgWage*(employees-1)*11;
        
        var turnover = parseInt(industrySetup['turnover']) + parseInt(salaries);
        
        $("#CostbenefitItem_turnover_value").val(turnover);
        $("#_turnoveryearly").val(turnover*12);
    }
    
    updateExpenses = function(){
        var turnover = $("#CostbenefitItem_turnover_monthly input").val();

        var expenses = Math.round( (turnover * 0.8) );
        
        $("#CostbenefitItem_expenses_monthly input").val(expenses);
        $("#CostbenefitItem_expenses_yearly input").val(expenses*12);
    }
    
    updateLoans = function(){
        var expenses = parseInt($("#CostbenefitItem_expenses_value").val());
        var salaries = parseInt($("#CostbenefitItem_salaries_value").val());
        var sideExpenses = parseInt($("#CostbenefitItem_sideExpenses_value").val());
        var rents = parseInt($("#CostbenefitItem_rents_value").val());
        var communication = parseInt($("#CostbenefitItem_communication_value").val());
        
        // Calculate loan sum. 3x all expenses + one months expenses
        loanSum = (expenses+salaries+sideExpenses+rents+communication)*3 + expenses;
        var interest = 3.3 / 100 / 12;
        payment = loanSum * ( interest / (1 - Math.pow((1+interest), -60)));
        
        loans = Math.round(payment);
        
        $("#CostbenefitItem_loans_value").val(loans);
        $("#_loansyearly").val(loans*12);
    }
    
    updateRents = function(){
        var industryId = $("#Company_industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#Company_employees option:selected").text();
        
        var rents = parseInt(industrySetup['rents']) * (1+Math.floor((parseInt(employees)/5)));
        
        $("#CostbenefitItem_rents_value").val(rents);
        $("#_rentsyearly").val(rents*12);       
    }
    
    updateCommunication = function(){
        var industryId = $("#Company_industry_id").val();
        var industrySetup = IndustrySetupArray[industryId];
        var employees = $("#Company_employees option:selected").text();
        
        var communication = Math.round(parseInt(industrySetup['communication'])*(1+(parseInt(employees)-1)*0.2));
        
        $("#CostbenefitItem_communication_value").val(communication);
        $("#_communicationyearly").val(communication*12); 
    }
    
    updateHealth = function(){
        $("#CostbenefitItem_health_value").val(0);
        $("#_healthyearly").val(0);
    }
    
    updateOther = function(){
        $("#CostbenefitItem_other_value").val(0);
        $("#_otheryearly").val(0);
    }
    
    updateProfit = function(){
        var turnover = Number($("#CostbenefitItem_turnover_value").val());
        var expenses = Number($("#CostbenefitItem_expenses_value").val());
        var salaries = Number($("#CostbenefitItem_salaries_value").val());
        var side = Number($("#CostbenefitItem_sideExpenses_value").val());
        var loans = Number($("#CostbenefitItem_loans_value").val());
        var rents = Number($("#CostbenefitItem_rents_value").val());
        var communication = Number($("#CostbenefitItem_communication_value").val());
        var health = Number($("#CostbenefitItem_health_value").val());
        var other = Number($("#CostbenefitItem_other_value").val());
        
        var profit = Math.round(turnover-expenses-salaries-side-loans-rents-communication-health-other);
           
        $("#_profitmonthly").val(profit);
        $("#_profityearly").val(profit*12);
    }
    
    fillYearlyFields = function(){
        $("#costBenefitCalculation input").each(function() {
            if(this.id.substring(0,15) === "CostbenefitItem"){
               updateYearlyField($(this));
            }
        });
    }
    
    updateProfit();
    fillYearlyFields();
});