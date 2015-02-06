<?php  defined('C5_EXECUTE') or die("Access Denied.");

// Set the name of custom template
$viewTemplate = 'view';


/* You should not modify the lines below */
$scopeItems = $this->getScopeItems();
$bid = $scopeItems['b']->getBlockID();
$id = 'ccm-ajax-page-list-'.$bid;
$a = $b->getBlockAreaObject();
$aHandle = $a->getAreaHandle();
$cid = $a->getCollectionID();

$data_parameters = '';
$c = Page::getCurrentPage();
$pageController = $c->getPageController();
$parameters = $pageController->getRequestActionParameters();
if (is_array($parameters) && count($parameters) > 1) {
    $data_parameters = sprintf(
        'data-parameter-0="%s" data-parameter-1="%s"',
        h($parameters[0]),
        h($parameters[1])
    );
}

?>
<div
    id="<?php echo $id; ?>"
    data-bid="<?php echo $bid; ?>"
    data-cid="<?php echo $cid; ?>"
    data-ahandle="<?php echo $aHandle; ?>"
    <?php echo $data_parameters; ?>></div>
<div id="<?php echo $id; ?>-loading"><?php echo t('Loading...'); ?></div>

<script type="text/javascript">
$(function(){
    var template = '<?php echo $viewTemplate; ?>';
    $.ajax({
        url: CCM_DISPATCHER_FILENAME + '/ajax/page_list/' + template,
        type: 'get',
        data: {
            bID: $('#<?php echo $id; ?>').attr('data-bid'),
            cID: $('#<?php echo $id; ?>').attr('data-cid'),
            aHandle: $('#<?php echo $id; ?>').attr('data-ahandle'),
            parameters: [
                $('#<?php echo $id; ?>').attr('data-parameter-0'),
                $('#<?php echo $id; ?>').attr('data-parameter-1')
            ]
        }
    }).done(function(response){
        $('#<?php echo $id; ?>-loading').hide();
        $('#<?php echo $id; ?>').append(response);
        ccm_ajaxPageListPagination_<?php echo $bid; ?>();
    });
});

var ccm_ajaxPageListPagination_<?php echo $bid; ?> = function(){
    $('#<?php echo $id; ?> .pagination a').click(function(e){
        e.preventDefault();
        
        $('#<?php echo $id; ?>').empty();
        $('#<?php echo $id; ?>-loading').show();
        
        var linkurl = $(this).attr('href');
        
        $.ajax({
            url: linkurl
        }).done(function(response){
            $('#<?php echo $id; ?>-loading').hide();
            $('#<?php echo $id; ?>').append(response);
            ccm_ajaxPageListPagination_<?php echo $bid; ?>();
        });
    });
}
</script>