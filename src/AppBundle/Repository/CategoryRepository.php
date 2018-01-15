<?php

namespace AppBundle\Repository;

/**
 * CategoryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryRepository extends \Doctrine\ORM\EntityRepository
{
    public function getCategoriesByLocaleWithProductsCount($locale)
    {


        $result = $this->createQueryBuilder('category')
            ->select('category_trans.name, COUNT(product.id) AS nb, category.id')
            ->join('category.translations', 'category_trans')
            ->join('category.products', 'product')
            ->where('category_trans.locale = :locale')
            ->setParameters([
                'locale' => $locale
            ])
            ->groupBy('category_trans.name')
            ->getQuery()
            ->getResult()
        ;

        return $result;

    }



    public function getProductsByCategory($locale, $slugCategory)
    {

        $result = $this->createQueryBuilder('category')
            ->select('product_trans.name, product_trans.description, product.price, product_trans.slug, product.image')
            ->join('category.products', 'product')
            ->join('category.translations', 'category_trans')
            ->join('product.translations', 'product_trans')
            ->where('product_trans.locale = :locale')
            ->andWhere('category_trans.slug = :slugCategory')
            ->setParameters([
                'locale' => $locale,
                'slugCategory' => $slugCategory
            ])
            ->getQuery()
            ->getResult()
        ;

        return $result;
    }





}
