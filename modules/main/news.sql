explain (
    SELECT
        activity.activity_id AS content_id,
        5 AS section_id,
        'activity' As type,
        activity.user_id,
        activity.user_name,
        activity.name,
        IFNULL(CONCAT('lectors/', l.image), CONCAT('80x100/', activity.image)) AS image,
        activity.date_start AS description,
        activity.date_end AS price,
        city_name AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        activity.date_add,
        flag_moder_view,
        activity.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        activity.flag,
        1 AS flag_show
    FROM `activity`
    LEFT JOIN `light_content` AS b
        ON b.section_id = 5 AND b.resource_id = activity.activity_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    LEFT JOIN `activity_lectors` AS l
        ON l.activity_id = activity.activity_id AND l.sort_id = 0
    WHERE
        activity.flag = 1 AND activity.flag_moder = 1 AND flag_delete = 0 AND
        IF(activity.date_start = '000-00-00', 1,
            IF(activity.date_end != '000-00-00', activity.date_end > '2014-10-28', activity.date_start > '2014-10-28')
        )
) UNION ALL (
    SELECT
        articles.article_id AS content_id,
        16 AS section_id,
        'articles' As type,
        user_id,
        '' AS user_name,
        name,
        IFNULL(url_full, 'none.jpg') AS image,
        content_min AS description,
        '' AS price,
        '' AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        date_public AS date_add,
        '1' AS flag_moder_view,
        articles.flag_moder,
        0 AS light_flag,
        articles.flag,
        1 AS flag_show
    FROM `articles`
    LEFT JOIN `articles_images`
        USING(image_id)
    WHERE
        articles.flag =1 AND articles.flag_moder = 1 AND flag_delete = 0 AND date_public <= '2014-10-28'
) UNION ALL (
    SELECT
        ads.ads_id AS content_id,
        4 AS section_id,
        'ads' As type,
        user_id,
        user_name,
        product_name AS name,
        IFNULL(CONCAT('offers/80x100/', i.url_full), CONCAT('products/80x100/', products.image)) AS image,
        products.description,
        ads.price,
        ads.price_description,
        ads.currency_name,
        ads.currency_id,
        '' AS flag_stock,
        ads.date_add,
        flag_moder_view,
        ads.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        ads.flag,
        1 AS flag_show
    FROM `ads`
    INNER JOIN `products`
        USING(product_id)
    LEFT JOIN `ads_images` AS i
        ON i.ads_id = ads.ads_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 4 AND b.resource_id = ads.ads_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0
) UNION ALL (
    SELECT
        p.product_new_id AS content_id,
        3 AS section_id,
        'products_new' As type,
        user_id,
        user_name,
        product_name AS name,
        IFNULL(i.url_full, products.image) AS image,
        products.description,
        IF(s.flag = 1, s.price, p.price) AS price,
        p.price_description,
        p.currency_name,
        IF(s.flag = 1, s.currency_id, p.currency_id) AS currency_id,
        s.flag AS flag_stock,
        IF(s.flag = 1, s.date_add, p.date_add) AS date_add,
        flag_moder_view,
        p.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        p.flag,
        p.flag_show
    FROM `products_new` AS p
    INNER JOIN `products`
        USING(product_id)
    LEFT JOIN `products_new_images` AS i
        ON i.product_new_id = p.product_new_id AND i.sort_id = 0
    LEFT JOIN `stocks` AS s
        ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND DATE_SUB(s.date_start, INTERVAL 1 DAY) < '2014-10-28' AND s.date_end > '2014-10-28'
    LEFT JOIN `light_content` AS b
        ON b.section_id = 3 AND b.resource_id = p.product_new_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0 AND p.flag_show = 1
) UNION ALL (
    SELECT
        s.service_id AS content_id,
        9 AS section_id,
        'services' AS type,
        user_id,
        user_name,
        name,
        i.url_full AS image,
        '' AS description,
        '' AS price,
        '' AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        s.date_add,
        flag_moder_view,
        s.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        s.flag,
        1 AS flag_show
    FROM `services` AS s
    LEFT JOIN `services_images` AS i
        ON i.service_id = s.service_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 9 AND b.resource_id = s.service_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        s.flag = 1 AND s.flag_moder = 1 AND s.flag_delete = 0
) UNION ALL (
    SELECT
        d.demand_id,
        11 AS section_id,
        'demand' AS type,
        user_id,
        user_name,
        name,
        i.url_full AS image,
        '' AS description,
        '' AS price,
        '' AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        d.date_add,
        flag_moder_view,
        d.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        d.flag,
        1 AS flag_show
    FROM `demand` AS d
    LEFT JOIN `demand_images` AS i
        ON i.demand_id = d.demand_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 11 AND b.resource_id = d.demand_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
) UNION ALL (
    SELECT
        l.lab_id AS content_id,
        7 AS section_id,
        'labs' AS type,
        l.user_id,
        ui.name AS user_name,
        l.name,
        i.url_full AS image,
        '' AS description,
        '' AS price,
        '' AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        l.date_add,
        l.flag_moder_view,
        l.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        l.flag,
        1 AS flag_show
    FROM `labs` AS l
    LEFT JOIN `users_info` AS ui
        USING(user_id)
    LEFT JOIN `labs_images` AS i
        ON i.lab_id = l.lab_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 7 AND b.resource_id = l.lab_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        l.flag = 1 AND l.flag_moder = 1 AND l.flag_delete = 0
) UNION ALL (
    SELECT
        r.realty_id AS content_id,
        8 AS section_id,
        'realty' AS type,
        user_id,
        user_name,
        CONCAT(name, ', г. ', city_name) AS name,
        i.url_full AS image,
        '' AS description,
        price AS price,
        price_description,
        currency_name AS currency_name,
        currency_id,
        '' AS flag_stock,
        r.date_add,
        flag_moder_view,
        r.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        r.flag,
        1 AS flag_show
    FROM `realty` AS r
    LEFT JOIN `realty_images` AS i
        ON i.realty_id = r.realty_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 8 AND b.resource_id = r.realty_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        r.flag = 1 AND r.flag_moder = 1 AND r.flag_delete = 0
) UNION ALL (
    SELECT
        d.diagnostic_id AS content_id,
        10 AS section_id,
        'diagnostic' AS type,
        user_id,
        user_name,
        name,
        i.url_full AS image,
        '' AS description,
        '' AS price,
        city_name AS price_description,
        '' AS currency_name,
        '' AS currency_id,
        '' AS flag_stock,
        d.date_add,
        flag_moder_view,
        d.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        d.flag,
        1 AS flag_show
    FROM `diagnostic` AS d
    LEFT JOIN `diagnostic_images` AS i
        ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 10 AND b.resource_id = d.diagnostic_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
) UNION ALL (
    SELECT
        w.work_id AS content_id,
        6 AS section_id,
        'resume' AS type,
        user_id,
        user_name,
        (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
        i.url_full AS image,
        ui.avatar AS description,
        price,
        city_name AS price_description,
        currency_name,
        currency_id,
        '' AS flag_stock,
        w.date_add,
        flag_moder_view,
        w.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        w.flag,
        1 AS flag_show
    FROM `work` AS w
    LEFT JOIN `users_info` AS ui
        USING(user_id)
    LEFT JOIN `work_images` AS i
        ON i.work_id = w.work_id AND i.sort_id = 0
    LEFT JOIN `light_content` AS b
        ON b.section_id = 6 AND b.resource_id = w.work_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        w.flag = 1 AND w.flag_moder = 1 AND w.flag_delete = 0
) UNION ALL (
    SELECT
        v.vacancy_id AS content_id,
        15 AS section_id,
        'vacancies'	AS type,
        v.user_id,
        c.name AS user_name,
        CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
        c.logotype AS image,
        '' AS description,
        v.price,
        v.currency_name,
        v.currency_id,
        '' AS price_description,
        '' AS flag_stock,
        v.date_add,
        flag_moder_view,
        v.flag_moder,
        IF(b.resource_id, 1, 0) AS light_flag,
        v.flag,
        1 AS flag_show
    FROM `vacancies` AS v
    INNER JOIN `vacancy_company_info` AS c
        USING(company_id)
    LEFT JOIN `light_content` AS b
        ON b.section_id = 15 AND b.resource_id = v.vacancy_id AND DATE_SUB(b.date_start, INTERVAL 1 DAY) < '2014-10-28' AND b.date_end > '2014-10-28'
    WHERE
        v.flag = 1 AND v.flag_moder = 1 AND v.flag_delete = 0
)
ORDER BY date_add DESC
LIMIT 0, 10




(
  SELECT
       activity.activity_id AS content_id,
       5 AS section_id,
       'activity' As type,
       activity.user_id,
       activity.user_name,
       activity.name,
       IFNULL(CONCAT('lectors/', l.image), CONCAT('80x100/', activity.image)) AS image,
       activity.date_start AS description,
       activity.date_end AS price,
       city_name AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       activity.date_add,
       flag_moder_view,
       activity.flag_moder,
       activity.flag,
       1 AS flag_show
   FROM `activity`
   LEFT JOIN `activity_lectors` AS l
       ON l.activity_id = activity.activity_id AND l.sort_id = 0
   WHERE
       activity.flag = 1 AND activity.flag_moder = 1 AND flag_delete = 0 AND
       (date_start = '0000-00-00' AND date_end = '0000-00-00') OR
       (date_start != '0000-00-00' AND date_end > '2014-10-28') OR
       (date_start > '2014-10-28' AND date_end != '0000-00-00')
) UNION ALL (
   SELECT
       articles.article_id AS content_id,
       16 AS section_id,
       'articles' As type,
       user_id,
       '' AS user_name,
       name,
       IFNULL(url_full, 'none.jpg') AS image,
       content_min AS description,
       '' AS price,
       '' AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       date_public AS date_add,
       '1' AS flag_moder_view,
       articles.flag_moder,
       articles.flag,
       1 AS flag_show
   FROM `articles`
   LEFT JOIN `articles_images`
       USING(image_id)
   WHERE
       articles.flag =1 AND articles.flag_moder = 1 AND flag_delete = 0 AND date_public < CAST('2014-10-27 23:59:59' AS DATETIME)
) UNION ALL (
   SELECT
       ads.ads_id AS content_id,
       4 AS section_id,
       'ads' As type,
       user_id,
       user_name,
       product_name AS name,
       IFNULL(CONCAT('offers/80x100/', i.url_full), CONCAT('products/80x100/', products.image)) AS image,
       products.description,
       ads.price,
       ads.price_description,
       ads.currency_name,
       ads.currency_id,
       '' AS flag_stock,
       ads.date_add,
       flag_moder_view,
       ads.flag_moder,
       ads.flag,
       1 AS flag_show
   FROM `ads`
   INNER JOIN `products`
       USING(product_id)
   LEFT JOIN `ads_images` AS i
       ON i.ads_id = ads.ads_id AND i.sort_id = 0
   WHERE
       ads.flag = 1 AND ads.flag_moder = 1 AND ads.flag_delete = 0
) UNION ALL (
   SELECT
       p.product_new_id AS content_id,
       3 AS section_id,
       'products_new' As type,
       user_id,
       user_name,
       product_name AS name,
       IFNULL(i.url_full, products.image) AS image,
       products.description,
       IF(s.flag = 1, s.price, p.price) AS price,
       p.price_description,
       p.currency_name,
       IF(s.flag = 1, s.currency_id, p.currency_id) AS currency_id,
       s.flag AS flag_stock,
       IF(s.flag = 1, s.date_add, p.date_add) AS date_add,
       flag_moder_view,
       p.flag_moder,
       p.flag,
       p.flag_show
   FROM `products_new` AS p
   INNER JOIN `products`
       USING(product_id)
   LEFT JOIN `products_new_images` AS i
       ON i.product_new_id = p.product_new_id AND i.sort_id = 0
   LEFT JOIN `stocks` AS s
       ON s.product_new_id = p.product_new_id AND s.flag = 1 AND s.flag_moder = 1 AND s.flag_show = 1 AND s.date_start <= '2014-10-28' AND s.date_end > '2014-10-28'
   WHERE
       p.flag = 1 AND p.flag_moder = 1 AND p.flag_delete = 0 AND p.flag_show = 1
) UNION ALL (
   SELECT
       s.service_id AS content_id,
       9 AS section_id,
       'services' AS type,
       user_id,
       user_name,
       name,
       i.url_full AS image,
       '' AS description,
       '' AS price,
       '' AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       s.date_add,
       flag_moder_view,
       s.flag_moder,
       s.flag,
       1 AS flag_show
   FROM `services` AS s
   LEFT JOIN `services_images` AS i
       ON i.service_id = s.service_id AND i.sort_id = 0
   WHERE
       s.flag = 1 AND s.flag_moder = 1 AND s.flag_delete = 0
) UNION ALL (
   SELECT
       d.demand_id,
       11 AS section_id,
       'demand' AS type,
       user_id,
       user_name,
       name,
       i.url_full AS image,
       '' AS description,
       '' AS price,
       '' AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       d.date_add,
       flag_moder_view,
       d.flag_moder,
       d.flag,
       1 AS flag_show
   FROM `demand` AS d
   LEFT JOIN `demand_images` AS i
       ON i.demand_id = d.demand_id AND i.sort_id = 0
   WHERE
       d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
) UNION ALL (
   SELECT
       l.lab_id AS content_id,
       7 AS section_id,
       'labs' AS type,
       l.user_id,
       ui.name AS user_name,
       l.name,
       i.url_full AS image,
       '' AS description,
       '' AS price,
       '' AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       l.date_add,
       l.flag_moder_view,
       l.flag_moder,
       l.flag,
       1 AS flag_show
   FROM `labs` AS l
   LEFT JOIN `users_info` AS ui
       USING(user_id)
   LEFT JOIN `labs_images` AS i
       ON i.lab_id = l.lab_id AND i.sort_id = 0
   WHERE
       l.flag = 1 AND l.flag_moder = 1 AND l.flag_delete = 0
) UNION ALL (
   SELECT
       r.realty_id AS content_id,
       8 AS section_id,
       'realty' AS type,
       user_id,
       user_name,
       CONCAT(name, ', г. ', city_name) AS name,
       i.url_full AS image,
       '' AS description,
       price AS price,
       price_description,
       currency_name AS currency_name,
       currency_id,
       '' AS flag_stock,
       r.date_add,
       flag_moder_view,
       r.flag_moder,
       r.flag,
       1 AS flag_show
   FROM `realty` AS r
   LEFT JOIN `realty_images` AS i
       ON i.realty_id = r.realty_id AND i.sort_id = 0
   WHERE
       r.flag = 1 AND r.flag_moder = 1 AND r.flag_delete = 0
) UNION ALL (
   SELECT
       d.diagnostic_id AS content_id,
       10 AS section_id,
       'diagnostic' AS type,
       user_id,
       user_name,
       name,
       i.url_full AS image,
       '' AS description,
       '' AS price,
       city_name AS price_description,
       '' AS currency_name,
       '' AS currency_id,
       '' AS flag_stock,
       d.date_add,
       flag_moder_view,
       d.flag_moder,
       d.flag,
       1 AS flag_show
   FROM `diagnostic` AS d
   LEFT JOIN `diagnostic_images` AS i
       ON i.diagnostic_id = d.diagnostic_id AND i.sort_id = 0
   WHERE
       d.flag = 1 AND d.flag_moder = 1 AND d.flag_delete = 0
) UNION ALL (
   SELECT
       w.work_id AS content_id,
       6 AS section_id,
       'resume' AS type,
       user_id,
       user_name,
       (SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `work_categs` WHERE work_id = w.work_id)) AS name,
       i.url_full AS image,
       ui.avatar AS description,
       price,
       city_name AS price_description,
       currency_name,
       currency_id,
       '' AS flag_stock,
       w.date_add,
       flag_moder_view,
       w.flag_moder,
       w.flag,
       1 AS flag_show
   FROM `work` AS w
   LEFT JOIN `users_info` AS ui
       USING(user_id)
   LEFT JOIN `work_images` AS i
       ON i.work_id = w.work_id AND i.sort_id = 0
   WHERE
       w.flag = 1 AND w.flag_moder = 1 AND w.flag_delete = 0
) UNION ALL (
   SELECT
       v.vacancy_id AS content_id,
       15 AS section_id,
       'vacancies'	AS type,
       v.user_id,
       c.name AS user_name,
       CONCAT((SELECT GROUP_CONCAT(name SEPARATOR ', ') FROM `categories_work` WHERE categ_id IN(SELECT categ_id FROM `vacancies_categs` WHERE vacancy_id = v.vacancy_id)), ', г. ', city_name) AS name,
       c.logotype AS image,
       '' AS description,
       v.price,
       v.currency_name,
       v.currency_id,
       '' AS price_description,
       '' AS flag_stock,
       v.date_add,
       flag_moder_view,
       v.flag_moder,
       v.flag,
       1 AS flag_show
   FROM `vacancies` AS v
   INNER JOIN `vacancy_company_info` AS c
       USING(company_id)
   WHERE
       v.flag = 1 AND v.flag_moder = 1 AND v.flag_delete = 0
)
ORDER BY date_add DESC
LIMIT 0, 10


SELECT *
FROM `ads`
WHERE date_add BETWEEN STR_TO_DATE('2014-10-26 00:00:00', '%Y-%m-%d %H:%i:%s') AND STR_TO_DATE('2014-10-27 23:59:59', '%Y-%m-%d %H:%i:%s')
ORDER BY date_add


SELECT *
FROM (
 (SELECT COUNT(*) AS '3' FROM `products_new` WHERE flag_delete = 0) AS products_new,
 (SELECT COUNT(*) AS '2' FROM `stocks`) AS stocks,
 (SELECT COUNT(*) AS '4' FROM `ads` WHERE flag_delete = 0) AS ads,
 (SELECT COUNT(*) AS '5' FROM `activity` WHERE flag_delete = 0) AS activity,
 (SELECT COUNT(*) AS '6' FROM `work` WHERE flag_delete = 0) AS work,
 (SELECT COUNT(*) AS '15' FROM `vacancies` WHERE flag_delete = 0) AS vacancies,
 (SELECT COUNT(*) AS '7' FROM `labs` WHERE flag_delete = 0) AS labs,
 (SELECT COUNT(*) AS '8' FROM `realty` WHERE flag_delete = 0) AS realty,
 (SELECT COUNT(*) AS '9' FROM `services` WHERE flag_delete = 0) AS services,
 (SELECT COUNT(*) AS '10' FROM `diagnostic` WHERE flag_delete = 0) AS diagnostic,
 (SELECT COUNT(*) AS '11' FROM `demand` WHERE flag_delete = 0) AS demand,
 (SELECT COUNT(*) AS '16' FROM `articles` WHERE flag_delete = 0) AS articles
)




-- Events --

SELECT activity.activity_id, user_id, user_name, contact_phones, city_name, city_id, date_start, date_end, flag_agreed, activity.name, activity.image, date_add, views, flag,
      flag_moder, flag_moder_view, flag_vip_add,
      l.image AS lector_image,
      (SELECT GROUP_CONCAT(categ_id) FROM `activity_categs` WHERE activity_id = activity.activity_id) AS categs,
      (SELECT sort_id FROM `top_to_section` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '2014-10-28' AND date_end > '2014-10-28' LIMIT 1) AS sort__id,
      (SELECT COUNT(*) FROM `light_content` WHERE section_id = 5 AND resource_id = activity.activity_id AND DATE_SUB(date_start, INTERVAL 1 DAY) < '2014-10-28' AND date_end > '2014-10-28') AS light_flag
      FROM `activity`
      LEFT JOIN `activity_lectors` AS l ON l.activity_id = activity.activity_id AND l.sort_id = 0
      WHERE flag_delete = 0  AND
      IF(date_start != '000-00-00', IF(date_end != '000-00-00', date_end > '2014-10-28', date_start > '2014-10-28'), 1)


      ORDER BY IFNULL(sort__id, 99999), IF(sort__id = 999, RAND(), 1), date_add DESC
      LIMIT 0, 14;


SELECT SQL_NO_CACHE
    a.activity_id,
    a.user_id,
    a.user_name,
    a.name,
    a.image,
    a.contact_phones,
    a.city_name,
    a.city_id,
    a.date_start,
    a.date_end,
    a.flag_agreed,
    a.flag,
    a.flag_moder,
    a.flag_moder_view,
    a.flag_vip_add,
    a.date_add,
    a.views,
    IF(l.resource_id, 1, 0) AS light_flag,
    (SELECT GROUP_CONCAT(categ_id)
        FROM `activity_categs`
        WHERE activity_id = a.activity_id) AS categories
FROM `activity` AS a
LEFT JOIN `activity_lectors` AS al
    ON al.activity_id = a.activity_id AND al.sort_id = 0
LEFT JOIN `light_content` AS l
    ON l.section_id = 5 AND l.resource_id = a.activity_id AND DATE_SUB(l.date_start, INTERVAL 1 DAY) < '2014-10-28' AND l.date_end > '2014-10-28'
WHERE
    a.flag_delete = 0 AND
    ((a.date_start = '0000-00-00' AND a.date_end = '0000-00-00') OR (a.date_start != '0000-00-00' AND a.date_end > '2014-10-28') OR (a.date_start > '2014-10-28' AND a.date_end != '0000-00-00'))
ORDER BY
    a.date_add DESC
LIMIT 0, 14



LEFT JOIN (
    SELECT ca.activity_id,
        GROUP_CONCAT(ca.name) AS categories_name,
    FROM
        `categories_activity` AS ca
    GROUP BY
        ca.activity_id
) AS cn ON cn.activity_id = a.activity_id












