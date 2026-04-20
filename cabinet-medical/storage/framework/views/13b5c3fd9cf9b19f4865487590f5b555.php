<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ordonnance #<?php echo e($prescription->id); ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 11pt; color: #1a202c; background: #fff; }
        .page { padding: 25mm 20mm; }

        /* Header */
        .header { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 15px; border-bottom: 3px solid #1a6b9a; margin-bottom: 20px; }
        .clinic-info h2 { color: #1a6b9a; font-size: 16pt; margin-bottom: 4px; }
        .clinic-info p { font-size: 9pt; color: #4a5568; line-height: 1.5; }
        .doc-info { text-align: right; }
        .doc-info h3 { color: #1a202c; font-size: 13pt; margin-bottom: 4px; }
        .doc-info p { font-size: 9pt; color: #4a5568; }
        .doc-info .specialty { font-weight: bold; color: #1a6b9a; }

        /* Patient info */
        .patient-section { background: #f7faff; border-left: 4px solid #1a6b9a; padding: 10px 14px; margin-bottom: 20px; border-radius: 0 6px 6px 0; }
        .patient-section .label { font-size: 8pt; color: #718096; text-transform: uppercase; letter-spacing: 0.05em; }
        .patient-section .value { font-size: 11pt; font-weight: bold; margin-top: 2px; }
        .patient-meta { font-size: 9pt; color: #4a5568; margin-top: 4px; }

        /* Prescription title */
        .rx-title { text-align: center; margin: 20px 0 15px; }
        .rx-title h3 { font-size: 18pt; color: #1a6b9a; letter-spacing: 0.05em; }
        .rx-title p { font-size: 9pt; color: #718096; margin-top: 4px; }

        /* Medications */
        .medication { border: 1px solid #e2e8f0; border-radius: 6px; padding: 12px 15px; margin-bottom: 10px; page-break-inside: avoid; }
        .med-name { font-size: 12pt; font-weight: bold; color: #1a202c; }
        .med-dosage { font-size: 10pt; color: #4a5568; margin-top: 3px; }
        .med-grid { display: flex; gap: 20px; margin-top: 8px; }
        .med-field label { font-size: 8pt; color: #718096; text-transform: uppercase; }
        .med-field span { display: block; font-size: 10pt; font-weight: bold; color: #2d3748; }
        .med-instructions { font-size: 9pt; color: #718096; margin-top: 6px; font-style: italic; }

        /* Notes */
        .notes-section { background: #fffbeb; border: 1px solid #fde68a; border-radius: 6px; padding: 10px 14px; margin-top: 15px; }
        .notes-section p { font-size: 9pt; color: #92400e; }

        /* Footer */
        .footer { margin-top: 30px; padding-top: 15px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: flex-end; }
        .signature-area { text-align: center; width: 180px; }
        .signature-line { border-bottom: 1px solid #1a202c; margin-bottom: 5px; height: 40px; }
        .signature-label { font-size: 8pt; color: #718096; }
        .date-area { font-size: 9pt; color: #4a5568; }
        .validity { text-align: center; margin-top: 20px; font-size: 8pt; color: #a0aec0; }
    </style>
</head>
<body>
<div class="page">
    
    <div class="header">
        <div class="clinic-info">
            <h2>🏥 Cabinet Médical</h2>
            <p>
                123 Avenue Mohammed V, Casablanca<br>
                Tél: +212 522 000 000<br>
                Email: contact@cabinet.ma
            </p>
        </div>
        <div class="doc-info">
            <h3>Dr. <?php echo e($prescription->doctor->user->name); ?></h3>
            <p class="specialty"><?php echo e($prescription->doctor->specialty); ?></p>
            <p>N° Ordre: <?php echo e($prescription->doctor->license_number); ?></p>
            <p><?php echo e($prescription->doctor->user->phone); ?></p>
        </div>
    </div>

    
    <div class="patient-section">
        <div class="label">Patient</div>
        <div class="value"><?php echo e($prescription->patient->user->name); ?></div>
        <div class="patient-meta">
            <?php if($prescription->patient->age): ?> Age : <?php echo e($prescription->patient->age); ?> ans · <?php endif; ?>
            <?php if($prescription->patient->gender_label): ?> <?php echo e($prescription->patient->gender_label); ?> · <?php endif; ?>
            <?php if($prescription->patient->blood_type): ?> Groupe sanguin : <?php echo e($prescription->patient->blood_type); ?> <?php endif; ?>
            <?php if($prescription->patient->allergies): ?><br>⚠ Allergies : <?php echo e($prescription->patient->allergies); ?> <?php endif; ?>
        </div>
    </div>

    
    <div class="rx-title">
        <h3>℞ ORDONNANCE</h3>
        <p>Ordonnance N° <?php echo e($prescription->id); ?> — <?php echo e($prescription->created_at->format('d/m/Y')); ?></p>
    </div>

    
    <?php $__currentLoopData = $prescription->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="medication">
        <div class="med-name"><?php echo e(($i + 1)); ?>. <?php echo e($item->medication); ?></div>
        <div class="med-dosage"><?php echo e($item->dosage); ?></div>
        <div class="med-grid">
            <div class="med-field">
                <label>Fréquence</label>
                <span><?php echo e($item->frequency); ?></span>
            </div>
            <div class="med-field">
                <label>Durée</label>
                <span><?php echo e($item->duration); ?></span>
            </div>
        </div>
        <?php if($item->instructions): ?>
        <div class="med-instructions">→ <?php echo e($item->instructions); ?></div>
        <?php endif; ?>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <?php if($prescription->notes): ?>
    <div class="notes-section">
        <p><strong>📋 Recommandations :</strong> <?php echo e($prescription->notes); ?></p>
    </div>
    <?php endif; ?>

    
    <div class="footer">
        <div class="date-area">
            <p>Fait à Casablanca,</p>
            <p>le <?php echo e($prescription->created_at->format('d/m/Y')); ?></p>
        </div>
        <div class="signature-area">
            <div class="signature-line"></div>
            <div class="signature-label">Signature & Cachet du médecin</div>
        </div>
    </div>

    <div class="validity">
        Cette ordonnance est valable 3 mois à compter de la date d'émission.
        Délivrance sur présentation d'une pièce d'identité.
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\HP\Downloads\cabinet-medical-final\cabinet-medical\resources\views/pdf/prescription.blade.php ENDPATH**/ ?>