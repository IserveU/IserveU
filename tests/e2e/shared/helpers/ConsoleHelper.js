class ConsoleHelper {

    static printErrors(ignoreLevels = ['WARNING']){


        browser.manage().logs().get('browser').then(function(browserlogs){
            
              for ( const log of browserlogs ) {

                if(!ignoreLevels.includes(log.level.name_)) console.log(log.level.name_ + JSON.stringify(log.message)+"\n");

              }
        
        });
  	}
}


module.exports = ConsoleHelper;
