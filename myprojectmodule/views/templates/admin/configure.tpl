
{block name='content'}
    <div class="panel">
        <div class="panel-heading">
            <i class="icon-list"></i> {l s='Customer Points List' mod='myprojectmodule'}
        </div>
        <div class="panel-body">
            {if isset($customers_points) && is_array($customers_points) && $customers_points|@count > 0}
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>{l s='Customer Name' mod='myprojectmodule'}</th>
                        <th>{l s='Points' mod='myprojectmodule'}</th>
                    </tr>
                    </thead>
                    <tbody>
                    {foreach from=$customers_points item=customer}
                        <tr>
                            <td>{$customer.firstname} {$customer.lastname}</td>
                            <td>{$customer.points}</td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            {else}
                <p>{l s='No customers found.' mod='myprojectmodule'}</p>
            {/if}
        </div>
    </div>
{/block}

