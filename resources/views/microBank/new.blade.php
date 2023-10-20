<?php
/*
Template Name: contact
*/
?>

<?php

session_start();

?>

<?php get_header(); ?>

<div id='section'>
    <section>
        <article>
            <h1><?php the_title(); ?></h1>
            <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
                <?php the_content(); ?>
            <?php endwhile; else: ?>
                <p>Ошибка!</p>
            <?php endif; ?>

            <div id="myform">
                <form method="POST" id="userForm" action="https://mkdkz.kz/online/">

                    <label><?php the_field('summa'); ?></label>
                    <input type="text" value="" size="20" min="150000" name="Summ" id="summa" class="rsform-input-box" required >

                    <label><?php the_field('srok'); ?></label>
                    <input type="text" value="" size="20" name="Srok" id="srok" class="rsform-input-box" required >

                    <label><?php the_field('fio'); ?></label>
                    <input type="text" value="" size="20" name="FIO" id="name" class="rsform-input-box" required >

                    <label><?php the_field('iin'); ?></label>
                    <input type="text" value="" size="20" maxlength="12" name="IIN" id="iin" class="rsform-input-box" required >

                    <label><?php the_field('god'); ?></label>
                    <input type="text" value="" size="20" placeholder="_//_" name="DateBorn" id="date" required readonly >

                    <label><?php the_field('grazhdanin'); ?></label>
                    <input type="checkbox" name="Citizen" value="1" checked id="rk0" class="rsform-checkbox" required >

                    <label><?php the_field('gorod'); ?></label>
                    <input type="text" value="" size="20" name="Place" id="address1" class="rsform-input-box" required >
                    <label><?php the_field('adres'); ?></label>
                    <input type="text" value="" size="20" name="Addr" id="address2" class="rsform-input-box" required >

                    <label><?php the_field('pochta'); ?></label>
                    <input type="email" value="" size="20" name="EMail" id="email" class="rsform-input-box" required >

                    <label><?php the_field('phone'); ?></label>
                    <input type="tel" value="" size="20" name="Phone" id="phone2" class="rsform-input-box myphone" required >

                    <label><?php the_field('stazh'); ?></label>
                    <select name="Staz" id="job" class="rsform-select-box">
                        <?php the_field('select'); ?>
                    </select>

                    <label><?php the_field('dokhod'); ?></label>
                    <input type="text" value="" size="20" name="Profit" id="revenue" class="rsform-input-box" required>
                    <label><?php the_field('soglasie'); ?></label>

                    <p>	<input type="checkbox" name="form[agree][]" value="Даю согласие на сбор, хранение и обработку персональных данных в соответствие с Законом РК «О персональных данных и их защите»" id="agree0" class="rsform-checkbox" required ><?php the_field('uslovia'); ?></p>

                    <button type="submit" name="form[submit]" id="submit" --="" event="" snippet="" for="" conversion="" page="" in="" your="" html="" add="" the="" and="" call="" gtag_report_conversion="" when="" someone="" clicks="" on="" chosen="" link="" or="" button="" script="" function="" url="" var="" callback="" if="" typeof="" undefined="" window="" location="" gtag="" send_to="" :="" aw-340907325="" lkz_cpkjji4del2qx6ib="" event_callback="" return="" false="" class="rsform-submit-button  btn btn-primary"><?php the_field('sent'); ?></button>

                    <input type="hidden" name="form[formId]" value="3" >
                </form>
            </div>


            <script>
                document.getElementById("iin").addEventListener("input", function() {
                    var iin = this.value;

                    // Проверка, что ИИН состоит из 12 цифр
                    if (iin.length === 12 && /^\d{12}$/.test(iin)) {
                        var year = iin.substr(0, 2);
                        var month = iin.substr(2, 2);
                        var day = iin.substr(4, 2);


                        // Определение года в зависимости от первых двух цифр
                        var birthYear = parseInt(year);
                        if (birthYear >= 0 && birthYear <= 24) {
                            birthYear = 2000 + birthYear;
                        } else if (birthYear >= 25 && birthYear <= 99) {
                            birthYear = 1900 + birthYear;
                        }

                        // Преобразование в формат "19YY-MM-DD"
                        var formattedDate = birthYear + "-" + month + "-" + day;
                        document.getElementById("date").value = formattedDate;
                    }
                });

            </script>
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $url = 'http://reg.tat.kz:8080/crform/send.aspx?' . http_build_query($_POST);

                // Создаем запрос к целевому серверу
                $ch = curl_init($url);

                // Настроим опции для cURL-запроса
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                // Выполним запрос к целевому серверу
                $response = curl_exec($ch);

                if ($response === false) {
                    // Обработка ошибки запроса
                    echo 'Ошибка: ' . curl_error($ch);
                } else {
                    // Передаем ответ обратно клиенту
                    echo $response;
                }
            } else {
                // Обработка других методов (POST, PUT, и т. д.) по вашему усмотрению
                //echo 'Метод не поддерживается.';
            }

            ?>



            <!--

            <div id="myform">
             <form action="https://frosty-euclid.195-210-46-92.plesk.page/" method="GET">

            <p><label for="myname">ФИО*</label>
            <input type="text" name="myname" id="myname" value="" required /></p>



            <p><label for="iin">ИИН*</label>
            <input type="text" name="iin"  value="" required /></p>

            <p><label for="myphone">Телефон*</label>
            <input type="text" name="myphone"  class="myphone" value="" required /></p>


            <p><label for="rk">Гражданство*</label>
            <input type="checkbox" name="rk" checked required /> Являюсь гражданином РК</p>

            <p><label for="propiska">Место прописки (Алматы и Алматинская область)*</label>
            <input type="text" name="propiska"  value="" required /></p>

            <p><label for="address">Адрес*</label>
            <input type="text" name="address"  value="" required /></p>


            <p><label for="mymail">E-mail</label>
            <input type="email" name="mymail" id="mymail" value="" required /></p>

            <p><label for="job">Стаж работы*</label>
            <select name="job">
            <option value="С подтверждением дохода" selected>С подтверждением дохода</option>
            <option value="пенсионер">Пенсионер</option>
            <option value="самозанятый">Самозанятый</option>
            </select>
            </p>

            <p><label for="money">Ваш общий доход (в месяц)*</label>
            <input type="number" name="money"  value="" required /></p>


            <input type="hidden" name="srok"  value="" required />






            <p><input type="checkbox" name="soglasie" checked required />Онлайн расчет суммы займа ознакомительный. Точный расчет будет произведен с учетом Вашей платежеспособности в момент консультации в отделении МФО. Гарантия полной безопасности оформления онлайн и конфиденциальности персональных данных.</p>

            <p><button>Отправить</button></p>
            <input type="hidden" name="submitted" id="submitted" value="true" />

            <p>* - Поля обязательны для заполнения</p>
             </form>
            </div>

            -->

            <?php the_field('text'); ?>

        </article>
    </section>
</div>

<?php get_footer(); ?>
