# LetoChat - WordPress / Woocommerce Plugin

## API Endpoints

<table>
    <thead>
        <tr>
            <th>Nme</th>
            <th>Method type</th>
            <th>URL</th>
            <th>Params</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>
                Order by order id
            </td>
            <td>
                GET
            </td>
            <td>
                {{DOMAIN}}/wp-json/letochat/v1/order?id=...&auth_secret=...
            </td>
            <td>
                id -> ORDER ID<br/><br/>
                auth_secret -> AUTH SECRET FROM PLUGIN SETTINGS
            </td>
        </tr>
        <hr/>
        <tr>
            <td>
                Orders by user id
            </td>
            <td>
                GET
            </td>
            <td>
                {{DOMAIN}}/wp-json/letochat/v1/order/all?user_id=...&auth_secret=...
            </td>
            <td>
                user_id -> USER ID<br/><br/>
                auth_secret -> AUTH SECRET FROM PLUGIN SETTINGS
            </td>
        </tr>
        <hr/>
        <tr>
            <td>
                Cart by user id
            </td>
            <td>
                GET
            </td>
            <td>
                {{DOMAIN}}/wp-json/letochat/v1/user-cart?user_id=...&auth_secret=...
            </td>
            <td>
                user_id -> USER ID<br/><br/>
                auth_secret -> AUTH SECRET FROM PLUGIN SETTINGS
            </td>
        </tr>
        <hr/>
        <tr>
            <td>
                Carts by users id
            </td>
            <td>
                GET
            </td>
            <td>
                {{DOMAIN}}/wp-json/letochat/v1/user-cart/all?ids=1,2,3...&auth_secret=...
            </td>
            <td>
                ids -> USERS IDS COMMA-SEPARATED<br/><br/>
                auth_secret -> AUTH SECRET FROM PLUGIN SETTINGS
            </td>
        </tr>
    </tbody>
</table>