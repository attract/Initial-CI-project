/**
 * Created with JetBrains PhpStorm.
 * User: Denis
 * Date: 05.12.12
 * Time: 15:55
 * To change this template use File | Settings | File Templates.
 */
$(function()
{
    loader('hide');
})
function loader(type, msg)
{
    var msg = msg || 'Загрузка...';
    var type = type || 'show';

    $('.loader .loading_message').text(msg);

    if(type==1)
    {
        $('#loader,.loader').show();
    }
    else
    {
        $('#loader,.loader').hide();
    }
}
