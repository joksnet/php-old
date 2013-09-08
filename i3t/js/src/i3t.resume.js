(function( $ )
{
    i3t.modules.resume = {
        info : {
            author  : 'Juan M Martinez',
            email   : 'joksnet@gmail.com',
            version : '0.1'
        },

        init : function()
        {
            console.log('Module Resume Initilized');
        },

        controls : {},

        exec : function( year, month )
        {
            var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

            var year = year || (new Date()).getFullYear();
            var month = month || (new Date()).getMonth() + 1;

            for ( var i = 0; i < 12; i++ )
            {
                this.controls['resume/' + year + '/' + ( i + 1 ).toString()] = months[i] + ' ' + year;
            }

            console.log('Call ' + year + '/' + month);
        }
    };
})(jQuery);