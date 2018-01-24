<?php
/**
 * Created by PhpStorm.
 * User: wabap2-13
 * Date: 23/01/18
 * Time: 11:50
 */

namespace AppBundle\Controller\Admin;


use AppBundle\Entity\Category;
use AppBundle\Form\CategoryType;
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
class CategoryController extends Controller
{


    /**
     * @Route("/category/form", name="admin.category.form", defaults={"id" = null})
     * @Route("/category/update/{id}", name="admin.category.update")
     */
    public function formAction(ManagerRegistry $doctrine, Request $request, TranslatorInterface $translator, int $id = null):Response
    {
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Category::class);


        $category = $id ? $rc->find($id) : new Category();



        $type = CategoryType::class;

        $form = $this->createForm($type, $category);

        $form->handleRequest($request);

        // valide
        if($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            //dump($data); exit;

            $em->persist($data);

            $em->flush();

            // message flash

            $this->addflash('notice', $id ? $translator->trans('flash_message.edit_product') : $translator->trans('flash_message.add_product'));
            //exit;

            // redirection
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/product/form.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/category", name="admin.category.index")
     */
    public function indexAction(ManagerRegistry $doctrine):Response
    {

        $rc = $doctrine->getRepository(Category::class);
        $results = $rc->findAll();

        return $this->render('admin/category/index.html.twig', [
            'catagories' => $results
        ]);
    }


    /**
     * @Route("category/delete/{id}", name="category.delete")
     */

    public function deleteAction(ManagerRegistry $doctrine, TranslatorInterface $translator, int $id):Response
    {
        // doctrine
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository(Category::class);

        // séléction de l'entité
        $entity = $rc->find($id);

        //suppression
        $em->remove($entity);
        $em->flush();

        $this->addflash('notice', $translator->trans('flash_message.delete_category') );
        //exit;

        // redirection
        return $this->redirectToRoute('admin.category.index');
    }
}