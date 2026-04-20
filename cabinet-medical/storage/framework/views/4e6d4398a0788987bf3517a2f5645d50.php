<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Compte-rendu consultation #<?php echo e($consultation->id); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10.5pt; color: #1a202c; }
        .page { padding: 20mm 18mm; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 12px; border-bottom: 3px solid #1a6b9a; margin-bottom: 18px; }
        .clinic-info h2 { color: #1a6b9a; font-size: 15pt; margin-bottom: 3px; }
        .clinic-info p { font-size: 8.5pt; color: #4a5568; line-height: 1.5; }
        .doc-info { text-align: right; }
        .doc-info h3 { font-size: 12pt; margin-bottom: 3px; }
        .doc-info .specialty { color: #1a6b9a; font-weight: bold; font-size: 9pt; }
        h1.report-title { text-align: center; color: #1a6b9a; font-size: 15pt; margin: 16px 0 12px; letter-spacing: .04em; }
        .meta-grid { display: flex; gap: 15px; margin-bottom: 14px; }
        .meta-box { flex: 1; background: #f7faff; border-left: 3px solid #1a6b9a; padding: 8px 12px; border-radius: 0 4px 4px 0; }
        .meta-box .label { font-size: 7.5pt; color: #718096; text-transform: uppercase; letter-spacing: .05em; }
        .meta-box .value { font-size: 10pt; font-weight: bold; margin-top: 2px; }
        .section { margin-bottom: 14px; }
        .section h4 { font-size: 9pt; color: #718096; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 3px; }
        .section p { font-size: 10pt; line-height: 1.6; }
        .diagnosis-box { background: #f0fff4; border: 1px solid #9ae6b4; border-radius: 5px; padding: 10px 14px; }
        .vitals-grid { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 6px; }
        .vital { background: #f7faff; border: 1px solid #e2e8f0; border-radius: 5px; padding: 7px 12px; min-width: 90px; text-align: center; }
        .vital .v-label { font-size: 7.5pt; color: #718096; }
        .vital .v-value { font-size: 10.5pt; font-weight: bold; margin-top: 2px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; font-size: 9pt; }
        table th { background: #f0f4f8; padding: 6px 10px; text-align: left; font-size: 8pt; text-transform: uppercase; letter-spacing: .05em; }
        table td { padding: 6px 10px; border-bottom: 1px solid #f0f4f8; }
        .footer { margin-top: 25px; padding-top: 12px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature { text-align: center; width: 160px; }
        .sig-line { border-bottom: 1px solid #000; height: 36px; margin-bottom: 4px; }
        .sig-label { font-size: 8pt; color: #718096; }
    </style>
</head>
<body>
<div class="page">
    <div class="header">
        <div class="clinic-info">
            <h2>🏥 Cabinet Médical</h2>
            <p>123 Avenue Mohammed V, Casablanca<br>Tél: +212 522 000 000 · contact@cabinet.ma</p>
        </div>
        <div class="doc-info">
            <h3>Dr. <?php echo e($consultation->doctor->user->name); ?></h3>
            <p class="specialty"><?php echo e($consultation->doctor->specialty); ?></p>
            <p>N° Ordre: <?php echo e($consultation->doctor->license_number); ?></p>
        </div>
    </div>

    <h1 class="report-title">COMPTE-RENDU DE CONSULTATION</h1>

    <div class="meta-grid">
        <div class="meta-box">
            <div class="label">Patient</div>
            <div class="value"><?php echo e($consultation->patient->user->name); ?></div>
        </div>
        <div class="meta-box">
            <div class="label">Date</div>
            <div class="value"><?php echo e($consultation->created_at->format('d/m/Y')); ?></div>
        </div>
        <div class="meta-box">
            <div class="label">Réf. consultation</div>
            <div class="value">#<?php echo e($consultation->id); ?></div>
        </div>
    </div>

    <div class="section">
        <h4>Motif de consultation</h4>
        <p><?php echo e($consultation->chief_complaint); ?></p>
    </div>

    <div class="section">
        <h4>Diagnostic</h4>
        <div class="diagnosis-box"><p><?php echo e($consultation->diagnosis); ?></p></div>
    </div>

    <?php if($consultation->notes): ?>
    <div class="section">
        <h4>Notes & Observations</h4>
        <p><?php echo e($consultation->notes); ?></p>
    </div>
    <?php endif; ?>

    <?php if($consultation->weight || $consultation->temperature || $consultation->blood_pressure_systolic || $consultation->heart_rate): ?>
    <div class="section">
        <h4>Signes vitaux</h4>
        <div class="vitals-grid">
            <?php if($consultation->weight): ?><div class="vital"><div class="v-label">Poids</div><div class="v-value"><?php echo e($consultation->weight); ?> kg</div></div><?php endif; ?>
            <?php if($consultation->height): ?><div class="vital"><div class="v-label">Taille</div><div class="v-value"><?php echo e($consultation->height); ?> cm</div></div><?php endif; ?>
            <?php if($consultation->bmi): ?><div class="vital"><div class="v-label">IMC</div><div class="v-value"><?php echo e($consultation->bmi); ?></div></div><?php endif; ?>
            <?php if($consultation->temperature): ?><div class="vital"><div class="v-label">Temp.</div><div class="v-value"><?php echo e($consultation->temperature); ?>°C</div></div><?php endif; ?>
            <?php if($consultation->heart_rate): ?><div class="vital"><div class="v-label">Pouls</div><div class="v-value"><?php echo e($consultation->heart_rate); ?> bpm</div></div><?php endif; ?>
            <?php if($consultation->blood_pressure_systolic): ?><div class="vital"><div class="v-label">Tension</div><div class="v-value"><?php echo e($consultation->blood_pressure); ?></div></div><?php endif; ?>
        </div>
    </div>
    <?php endif; ?>

    <?php if($consultation->prescription && $consultation->prescription->items->count() > 0): ?>
    <div class="section">
        <h4>Ordonnance prescrite</h4>
        <table>
            <thead><tr><th>Médicament</th><th>Dosage</th><th>Fréquence</th><th>Durée</th></tr></thead>
            <tbody>
                <?php $__currentLoopData = $consultation->prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><strong><?php echo e($item->medication); ?></strong></td>
                    <td><?php echo e($item->dosage); ?></td>
                    <td><?php echo e($item->frequency); ?></td>
                    <td><?php echo e($item->duration); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <div class="footer">
        <div style="font-size:9pt;color:#4a5568">
            Casablanca, le <?php echo e($consultation->created_at->format('d/m/Y')); ?>

        </div>
        <div class="signature">
            <div class="sig-line"></div>
            <div class="sig-label">Signature & Cachet du médecin</div>
        </div>
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/pdf/consultation.blade.php ENDPATH**/ ?>