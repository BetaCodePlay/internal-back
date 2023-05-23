<!DOCTYPE html>
<html>
<head>
    <title><?php echo e($data['title']); ?></title>
    <style type="text/css">
        table {
            margin-left:30px;
            margin-right:30px;
        }
        .footer {
            color: #000000;
            width: 100%;
            height: 30px;
            position: absolute;
            bottom: 0;
            padding: 8px 0;
            text-align: left;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body style="background: #FFFFFF;">
<table width="100%">
    <thead>
    <tr>
        <td>
            <span
                style="font-family: Arial, Helvetica, sans-serif; font-size:18px; color: #000000"> <strong><?php echo e($data['title']); ?></strong></span><br>
        </td>
    </tr>
    </thead>
</table>
<table width="100%" style="margin-top: 30px">
    <thead>
    <tr>
        <td style="padding-bottom:10px;">
            <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <span><?php echo e($data['data_client']['sub_title_client']); ?></span>
                <br>
                <span><?php echo e($data['data_client']['messrs']); ?></span>
                <br>
                <span><?php echo e($data['data_client']['attention']); ?></span>
                <br>
                <span><?php echo e($data['data_client']['email']); ?></span>
                <br>
                <span><?php echo e($data['data_client']['phone']); ?></span>
                <br>
            </div>
        </td>
        <td>
            <div style="padding: 0px 0px 0px 40px;">
                <img style="padding-left: 40px; margin-top: 0px;" width="200"
                     src="https://dotworkers.com/img/logo-light.png" alt="dotworkers">
            </div>
        </td>
    </tr>
    </thead>
</table>
<table width="100%" style="border-collapse: collapse">
    <thead>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th style="text-align:center; width:60%; border: black 1px solid">
            <?php echo e($data['table_title']['description']); ?>

        </th>
        <th style="text-align:center; width:20%; border: black 1px solid">
            <?php echo e($data['table_title']['units']); ?>

        </th>
        <th style="text-align:center; width:20%; border: black 1px solid">
            <?php echo e($data['table_title']['total']); ?>

        </th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data['invoices']['invoices_service']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr style="background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:14px;">
            <td style="text-align:left; width:60%; border: black 1px solid"><?php echo e($invoice['description']); ?></td>
            <td style="text-align:center; width:20%; border: black 1px solid"><?php echo e($invoice['unit']); ?></td>
            <td style="text-align:center; width:20%; border: black 1px solid"><?php echo e($data['invoices_details']['sub_total']); ?></td>
        </tr>
        <tr style="background: #e62154; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
            <td style="background: #FFFFFF; text-align:center; width:60%; height: 15px; border-left: black 1px solid;"></td>
            <td style="background: #FFFFFF; text-align:center; width:20%; height: 15px;"></td>
            <td style="background: #FFFFFF; text-align:center; width:20%; height: 15px; border-right: black 1px solid;"></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <tr style="background: #e62154; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <td style="background: #FFFFFF; text-align:center; width:60%; height: 10px; border-left: black 1px solid;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 10px;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 10px; border-right: black 1px solid;"></td>
    </tr>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif;">
        <td style="text-align:center; width:60%; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"></td>
        <td style="text-align:right; width:20%; border-left: none; border-top: black 1px solid; border-bottom: black 1px solid; font-size:16px;"><?php echo e($data['invoices']['sub_total_title']); ?></td>
        <td style="text-align:center; width:20%; border-right: black 1px solid; border-left: black 1px solid; border-bottom: black 1px solid; border-top: black 1px solid; font-size:16px;"><?php echo e($data['invoices_details']['sub_total']); ?></td>
    </tr>
    <tr style="background:#e06666; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <td style="background: #FFFFFF; text-align:center; width:60%; height: 25px; border-left: black 1px solid;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 25px;"></td>
        <td style="background: #FFFFFF; text-align:center; width:20%; height: 25px; border-right: black 1px solid;"></td>
    </tr>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif;">
        <td style="text-align:center; width:60%; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"></td>
        <td style="text-align:right; width:20%; border-left: none; border-top: black 1px solid; border-bottom: black 1px solid; font-size:16px;"><?php echo e($data['invoices']['total_title']); ?></td>
        <td style="text-align:center; width:20%; border-right: black 1px solid; border-left: black 1px solid; border-bottom: black 1px solid; border-top: black 1px solid; font-size:16px;"><?php echo e($data['invoices_details']['sub_total']); ?></td>
    </tr>
    </tbody>
</table>
<table width="100%" style="margin-top: 20px">
    <tbody>
    <tr>
        <td style="padding-bottom:10px;">
            <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:18px; color: #000000">
                <span><?php echo e($data['payment_methods_title']); ?></span>
            </div>
        </td>
    </tr>
    </tbody>
</table>
<table width="100px" style="border-collapse: collapse">
    <?php $__currentLoopData = $data['payment_methods']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment_method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <thead>
        <tr style="width: 640px; background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px; ">
            <th style="text-align:center; width: 640px; border-left: black 1px solid; border-right: black 1px solid; border-top: black 1px solid">
               <span> <strong> <?php echo e($payment_method['payment']); ?> </strong></span>
            </th>
        </tr>
        </thead>
        <tbody>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:14px; width: 630px; border: black 1px solid; border-bottom: white 1px solid;">
            <td style="background: #FFFFFF; width: 630px; height: 20px; border:black 1px solid;  border-top: none;">
                <?php switch($payment_method['payment_method_type']):
                    case (\Dotworkers\Configurations\Enums\PaymentMethods::$wire_transfers): ?>
                    <div  style="font-family: Arial, Helvetica, sans-serif; font-size:12px; height: 50px ">
                        <div style="float: left; display:inline; margin: 4px">
                            <span> <strong><?php echo e($payment_method['data_title']['bank']); ?>: <?php echo e($payment_method['data']['bank']); ?></strong></span>
                            <br>
                            <span><strong><?php echo e($payment_method['data_title']['name']); ?>: <?php echo e($payment_method['data']['name']); ?></strong></span>
                            <br>
                            <span><strong><?php echo e($payment_method['data_title']['email']); ?>: <?php echo e($payment_method['data']['email']); ?></strong></span>
                        </div>
                        <div style=" float: left; display:inline;  margin: 1px">
                            <span> <strong><?php echo e($payment_method['data_title']['account_number']); ?>: <?php echo e($payment_method['data']['account_number']); ?></strong></span>
                            <br>
                            <span><strong><?php echo e($payment_method['data_title']['dni']); ?>: <?php echo e($payment_method['data']['dni']); ?></strong></span>
                            <br>
                            <span><strong><?php echo e($payment_method['data_title']['type_account']); ?>: <?php echo e($payment_method['data']['type_account']); ?></strong></span>
                        </div>
                    </div>
                    <?php break; ?>
                    <?php case (\Dotworkers\Configurations\Enums\PaymentMethods::$zelle): ?>
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong><?php echo e($payment_method['data_title']['name']); ?>: <?php echo e($payment_method['data']['name']); ?></strong></span>
                        <br>
                        <span> <strong><?php echo e($payment_method['data_title']['email']); ?>: <?php echo e($payment_method['data']['email']); ?></strong></span>
                    </div>
                    <?php break; ?>
                    <?php case (\Dotworkers\Configurations\Enums\PaymentMethods::$paypal): ?>
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong><?php echo e($payment_method['data_title']['name']); ?>: <?php echo e($payment_method['data']['name']); ?></strong></span>
                        <br>
                        <span> <strong><?php echo e($payment_method['data_title']['email']); ?>: <?php echo e($payment_method['data']['email']); ?></strong></span>
                    </div>
                    <?php break; ?>
                    <?php case (\Dotworkers\Configurations\Enums\PaymentMethods::$uphold): ?>
                    <?php case (\Dotworkers\Configurations\Enums\PaymentMethods::$cryptocurrencies): ?>
                    <div class="col-xs-5" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                        <span> <strong><?php echo e($payment_method['data_title']['email']); ?>: <?php echo e($payment_method['data']['email']); ?></strong></span>
                    </div>
                    <?php break; ?>
                <?php endswitch; ?>
            </td>
        </tr>
        <tr style="background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:16px; width: 650px;">
            <td style="background: #FFFFFF; text-align:center; width: 650px; height: 15px;"></td>
        </tr>
        </tbody>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</table>
<br>
<div class="footer" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
    <span><?php echo e($data['date']); ?></span>
</div>
<div class="page-break"></div>
<table width="100%" style="margin-top: 30px;">
    <thead>
        <tr>
            <td colspan="6" style="width:75%; font-family: Arial, Helvetica, sans-serif; font-size:12px;">
                <div style="padding-top: 5px;">
                    <span> <strong>Detalles de Factura Nro.######</strong></span>
                </div>
                <div style="padding-top: 5px;">
                    <span><strong>Mes de <?php echo e($data['month']); ?></strong></span>
                </div>
            </td>
            <td style="width:35%;">
                <div style="padding-left: 50px">
                    <img style="margin-top:20px;" width="150"
                         src="https://dotworkers.com/img/logo-light.png" alt="dotworkers">
                </div>
            </td>
        </tr>
    </thead>
</table>
<table width="100%" style="border-collapse: collapse;">
    <thead>
    <tr style="background: #999999; font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th colspan="7" style="text-align:center; border: black 1px solid">
            <?php echo e($data['data_client']['sub_title_details']); ?>

        </th>
    </tr>
    <tr style="background: #999999;  font-family: Arial, Helvetica, sans-serif; font-size:16px;">
        <th colspan="4" style="text-align:center; background: #e06666; font-family: Arial, Helvetica, sans-serif; border: black 1px solid;">
            detalle de factura
        </th>
        <th colspan="2" style="text-align:center; text-align:center; background: #e06666; font-family: Arial, Helvetica, sans-serif; border: black 1px solid; border: black 1px solid;">
            porcentaje
        </th>
        <th rowspan="2" style="text-align:center;  border: black 1px solid">
            Monto a facturar
        </th>
    </tr>
    <tr style=" background: #e06666; font-family: Arial, Helvetica, sans-serif; font-size:14px;">
        <th style="text-align:center;  border: black 1px solid">
            Producto
        </th>
        <th style="text-align:center;  border: black 1px solid">
            Ventas
        </th>
        <th  style="text-align:center;  border: black 1px solid">
            Premios
        </th>
        <th  style="text-align:center;  border: black 1px solid">
            Profit
        </th>
        <th  style="text-align:center;  border: black 1px solid">
           % Ventas
        </th>
        <th  style="text-align:center;  border: black 1px solid">
           % Profit
        </th>
    </tr>
    </thead>
    <tbody>
    <?php $__currentLoopData = $data['invoices_details']['totals']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice_detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr style="font-family: Arial, Helvetica, sans-serif; font-size:14px; border: black 1px solid">
            <td style="text-align:left; border-left: black 1px solid; border-top: #dee2e6 1px solid"><?php echo e($invoice_detail->provider); ?></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid"><?php echo e($invoice_detail->played); ?></td>
            <td style="text-align:center; border-left:#dee2e6 1px solid; border-top: #dee2e6 1px solid"><?php echo e($invoice_detail->won); ?></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid"><?php echo e($invoice_detail->profit); ?></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid"></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid"><?php echo e($invoice_detail->percentage); ?></td>
            <td style="text-align:center; border-left: #dee2e6 1px solid; border-top: #dee2e6 1px solid; border-right: black 1px solid;"><?php echo e($invoice_detail->total_provider); ?></td>
        </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
    <tfoot>
    <tr style="font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:14px;">
        <th colspan="6" style=" border-left: black 1px solid; border-right: black 1px solid; border-top: black 1px solid"></th>
        <th colspan="1" style="background: #999999; border: black 1px solid; border-left: black 1px solid; border-top: black 1px solid;"><?php echo e($data['invoices_details']['sub_total']); ?></th>
    </tr>
    <tr style="font-family: Arial, Helvetica, sans-serif; text-align:center; font-size:14px; border: black 1px solid">
        <th colspan="5" style="border: black 1px solid; border-top:none;"></th>
        <th colspan="1" style="background: #999999; border-left: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"><?php echo e($data['table_title']['totals']); ?></th>
        <th colspan="1" style="background: #999999; border-right: black 1px solid; border-top: black 1px solid; border-bottom: black 1px solid;"><?php echo e($data['invoices_details']['total_convert']); ?></th>
    </tr>
    </tfoot>
</table>
<div class="footer" style="font-family: Arial, Helvetica, sans-serif; font-size:12px;">
    <span><?php echo e($data['date']); ?></span>
</div>
</body>
</html>
