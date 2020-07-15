<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\Extension\Core\Type as NativeType;

class ProductType extends AbstractType
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', NativeType\TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('code', NativeType\TextType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('price', NativeType\NumberType::class, [
                'attr' => ['class' => 'form-control']
            ])
            ->add('category', NativeType\ChoiceType::class, [
                'choices' => $this->categoryRepository->findAll(),
                'choice_value' => 'id',
                'choice_label' => function(?Category $category) {
                    return $category ? $category->getName() : '';
                },
                'attr' => ['class' => 'form-control']
            ])
            ->add('save', NativeType\SubmitType::class, [
                'attr' => ['class' => 'btn btn-primary']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
