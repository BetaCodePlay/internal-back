<table class='table table-bordered table-sm table-striped table-hover'>
    <tbody>
    <tr>
        <th><?php echo e(__('ProviderId')); ?></th>
        <th><?php echo e(__('Nombre')); ?></th>
        <th><?php echo e(__('Username')); ?></th>
        <th><?php echo e(__('Total jugado')); ?></th>
        <th><?php echo e(__('Total premiado')); ?></th>
        <th><?php echo e(__('Apuesta total')); ?></th>
        <th><?php echo e(__('Beneficio total')); ?></th>
        <th><?php echo e(__('rtp')); ?></th>
    </tr>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <tr>
        <td><?php echo e($item->provider_id); ?></td>
        <td><?php echo e($item->name); ?></td>
        <td class="init_agent"><?php echo e($item->username); ?></td>
        <td><?php echo e($item->total_played); ?></td>
        <td><?php echo e($item->total_won); ?></td>
        <td><?php echo e($item->total_bet); ?></td>
        <td><?php echo e($item->total_profit); ?></td>
        <td><?php echo e($item->rtp); ?></td>
    </tr>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    
    </tbody>
</table>