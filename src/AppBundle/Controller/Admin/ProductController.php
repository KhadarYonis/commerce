<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 24/01/18
 * Time: 10:44
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use Doctrine\Common\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;


    /**
 * @Route("/admin")
 *
 */
class ProductController extends Controller
{
    /**
     * @Route("/product/form", name="admin.product.form", defaults={"id" = null})
     * @Route("/product/update/{id}", name="admin.product.update")
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, int $id = null):Response
    {
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Product::class);


        $product = $id ? $rc->find($id) : new Product();

        $type = ProductType::class;

        $form = $this->createForm($type, $product);

        $form->handleRequest($request);

        // valide
        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();

            $file = $form['image']->getData();

            $file->move('img/product', $file->getClientOriginalName());

            $data->setImage($file->getClientOriginalName());

            $em->persist($data);

            $em->flush();

            // message flash

            $this->addflash('notice', $id ? $translator->trans('flash_message.edit_product') : $translator->trans('flash_message.add_product'));
            //exit;

            // redirection
            return $this->redirectToRoute('admin.product.index');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("product/delete/{id}", name="product.delete")
     */

    public function deleteAction(ManagerRegistry $doctrine, TranslatorInterface $translator, int $id):Response
    {
        // doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Product::class);

        // séléction de l'entité
        $entity = $rc->find($id);


        unlink('img/product/'.$entity->getImage());

       // dump($entity); exit;

        //suppression
        $em->remove($entity);
        $em->flush();

        $this->addflash('notice', $translator->trans('flash_message.delete_product') );
        //exit;

        // redirection
        return $this->redirectToRoute('admin.product.index');
    }




    /**
     * @Route("/product", name="admin.product.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {

        $rc = $doctrine->getRepository(Product::class);
        $results = $rc->findAll();

        return $this->render('admin/product/index.html.twig', [
            'products' => $results
        ]);
    }
}