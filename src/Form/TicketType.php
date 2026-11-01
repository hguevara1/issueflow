<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Category;
use App\Entity\User;
use App\Form\Model\TicketCreateData;
use App\Model\TicketPriority;
use App\Model\TicketStatus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
       $builder
            ->add('code', TextType::class, [ //TextType indica que es un campo de texto en el formulario
                'label' => 'Código', // etiqueta del campo texto
                'attr' => [
                    'placeholder' => 'INC-1234', // Sugerencia de entrada
                    'pattern' => 'INC-[0-9]{4}', // Patrón de entrada esperado
                    'maxlength' => 8, // Longitud máxima aceptada
                ]
            ])
            ->add('title', TextType::class, [
                'label' => 'Título', // etiqueta del campo texto
                'attr' => [
                    'maxlength' => 180, // Longitud máxima aceptada
                ]
            ])
            ->add('priority', EnumType::class, [
                'class' => TicketPriority::class, //Indicamos el enum que se va a usar para construir el selector
                'label' =>  'Prioridad',
                'choice_label' => 'label'
            ])
            ->add('status', EnumType::class, [
                'class' => TicketStatus::class, //Indicamos el enum que se va a usar para construir el selector
                'label' =>  'Estado',
                'choice_label' => 'label'
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class, //Indicamos el enum que se va a usar para construir el selector
                'label' =>  'Categoria',
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Sin asignar',
            ])
            ->add('assignee', EntityType::class, [
                'class' => User::class, //Indicamos el enum que se va a usar para construir el selector
                'label' =>  'Asignado a',
                'choice_label' => 'name',
                'required' => false,
                'placeholder' => 'Sin asignar',
            ])
            ->add('save', SubmitType::class, [
                'label' =>  'Crear incidencia',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        //Symfony sepa que el objeto de datos esperado es TicketCreateData

         $resolver->setDefaults([
            'data_class' => TicketCreateData::class,
        ]);
    }
}