<?php
if (isset($_GET['a']) && ($_GET['a'] === 'add')) : ?>

    <?php
    $regulations = getCategories();


    ?>

    <section class="team-detail_form">
        <div class="auto-container mt-5">
            <div class="row clearfix">

                <div class="column col-lg-12 col-md-12 col-sm-12">
                    <div class="default-form contact-form">

                        <form method="post" id="create-regulation-form" enctype="multipart/form-data">
                            <div class="row clearfix">

                                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                    <select name="category" id="category" class="custom-select-box" data-validation="required">
                                        <option value="" selected disabled>Regulation for Category*</option>
                                        <?php foreach ($regulations as $regulation) : ?>
                                            <option value="<?php echo htmlspecialchars($regulation['slug']); ?>">
                                                <?php echo htmlspecialchars($regulation['name']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <!-- Editor TinyMCE -->
                                <div class="form-group col-lg-12">
                                    <label for="regulation-editor" class="sec-title_heading h5">Content Regulation:</label>
                                    <textarea id="regulation-editor" name="content"></textarea>
                                </div>
                                <!-- Submit -->
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="template-btn btn-style-one">
                                        <span class="btn-wrap">
                                            <span class="text-one">Adds regulation</span>
                                            <span class="text-two">Adds regulation</span>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </section>

<?php elseif (isset($_GET['a']) && ($_GET['a'] === 'edit')) : ?>

    <section class="team-detail_form">
        <div class="auto-container mt-5">
            <div class="row clearfix">


                <?php
                if (!isset($_GET['c']) || empty($_GET['c'])) :
                    $regulations = getCategories();
                ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 d-flex align-items-center gap-2">
                        <select name="c" id="category-edit" class="custom-select-box form-control" data-validation="required">
                            <option value="" selected disabled>Select category to edit*</option>
                            <?php foreach ($regulations as $regulation) : ?>
                                <option value="<?php echo htmlspecialchars($regulation['slug']); ?>">
                                    <?php echo htmlspecialchars($regulation['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button id="go-to-category" class="template-btn btn-style-one">
                            <span class="btn-wrap">
                                <span class="text-one">Select</span>
                                <span class="text-two">Select</span>
                            </span>
                        </button>
                    </div>
                <?php
                else :
                    $categories = getCategories();
                    $category_slug = $_GET['c'];
                    $regulations = getCategoryRegulationBySlug($category_slug);
                ?>
                    <div class="form-group col-lg-12 col-md-12 col-sm-12 d-flex align-items-center gap-2">
                        <select name="c" id="category-edit" class="custom-select-box form-control" data-validation="required">
                            <option value="" selected disabled>Select category to edit*</option>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo htmlspecialchars($category['slug']); ?>">
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button id="go-to-category" class="template-btn btn-style-one">
                            <span class="btn-wrap">
                                <span class="text-one">Select</span>
                                <span class="text-two">Select</span>
                            </span>
                        </button>
                    </div>


                    <div class="column col-lg-12 col-md-12 col-sm-12 mt-5">
                        <div class="default-form contact-form">

                            <form method="post" id="edit-regulation-form" enctype="multipart/form-data">
                                <div class="row clearfix">

                                    <div class="form-group col-lg-12 col-md-12 col-sm-12">
                                        <select name="category" id="category" class="custom-select-box" data-validation="required">
                                            <option value="" selected disabled>Regulation for Category*</option>
                                            <?php foreach ($categories as $category) : ?>
                                                <option value="<?php echo htmlspecialchars($category['slug']); ?>">
                                                    <?php echo htmlspecialchars($category['name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <!-- Editor TinyMCE -->
                                    <div class="form-group col-lg-12">
                                        <label for="regulation-editor" class="sec-title_heading h5">Content Regulation:</label>
                                        <textarea id="regulation-editor" name="content">
                                          <?php echo isset($regulations[0]['content']) ? $regulations[0]['content'] : ''; ?>
                                        </textarea>
                                    </div>
                                    <!-- Submit -->
                                    <div class="form-group col-lg-12">
                                        <button type="submit" class="template-btn btn-style-one">
                                            <span class="btn-wrap">
                                                <span class="text-one">Edit regulation</span>
                                                <span class="text-two">Edit regulation</span>
                                            </span>
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>
                <?php endif; ?>



            </div>
        </div>
    </section>

<?php else :
    header('Location: /admin/modify');
    exit;
endif;
