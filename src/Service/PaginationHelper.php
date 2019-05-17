<?php


namespace App\Service;


use App\Repository\ImageFileRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class PaginationHelper
{
    private $imageFileRepository;
    private $paginator;

    public function __construct(
        ImageFileRepository $imageFileRepository,
        PaginatorInterface $paginator
    ) {
        $this->imageFileRepository = $imageFileRepository;
        $this->paginator = $paginator;
    }

    /**
     * @param Request $request
     * @param int $limitPerPage
     * @return \Knp\Component\Pager\Pagination\PaginationInterface $pagination
     */
    public function queryAndPaginate(Request $request, int $limitPerPage)
    {
        $queryBuilder = $this->imageFileRepository->getAllOrderedByQueryBuilder();
        $query = $queryBuilder->getQuery();

        $pagination = $this->paginator->paginate(
            $query,
            $request->query->getInt('page', 1)/*page number*/,
            $limitPerPage
        );
        $pagination->setCustomParameters([
        'size' => 'small',
        ]);

        return $pagination;
    }
}
