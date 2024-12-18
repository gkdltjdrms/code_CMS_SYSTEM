<?php $settings = service('solutionSettings'); ?>
</main>
        <footer class="bg-light text-center py-4">
            <div>
                <p>&copy; <?= date('Y') ?> <?= esc($settings->solution_name) ?></p>
                <p><?= esc($settings->business_number) ?></p>
                <p><?= esc($settings->company_info) ?></p>
            </div>
        </footer>
    </body>
</html>
