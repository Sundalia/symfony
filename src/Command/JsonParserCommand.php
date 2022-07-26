<?php

namespace App\Command;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Book;
use App\Repository\BookRepository;


#[AsCommand(
    name: 'app:parse',
    description: 'Parses remote JSON with books.',
    hidden: false
)]
class JsonParserCommand extends Command
{
    private $bookRepository;

    public function __construct(BookRepository $bookRepository, ManagerRegistry $doctrine)
    {
        // $this->entityManager = $entityManager;
        $this->entityManager = $doctrine->getManager('default');
        $this->bookRepository = $bookRepository;
        parent::__construct();
    }

    private function get_decoded_json(OutputInterface $output)
    {
        $filename = 'var/cache/books.json';
        $json = '';
        if (file_exists($filename)) {
            $json = file_get_contents($filename);
            $output->writeln('File read from cache');
            return json_decode($json);
        }

        $url = 'https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json';
        $output->writeln('Downloading file from '.$url);
        $json = file_get_contents($url);
        if (!$json) {
            $output->writeln('Download ERROR');
            return FALSE;
        }

        $output->writeln('File downloaded');
        file_put_contents($filename, $json);
        $output->writeln('File written to cache');
        return json_decode($json);
    }

    private function log(OutputInterface $output, int $id, object $book, string $propertyName) {
        if (property_exists($book, $propertyName)) {
            $val = $book->$propertyName;
            $output->writeln("[$id][$propertyName] $val");
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $books = $this->get_decoded_json($output);
        if (!$books) {
            return Command::FAILURE;
        }

        $em = $this->entityManager;

        $authors = array();
        foreach($books as $id=>$book) {
            $dbBook = new Book();

            $output->writeln("[$id][Title] $book->title");

            $dbBook->setTitle($book->title);
            $dbBook->setIsbn($book->isbn ?? null);
            $dbBook->setPageCount($book->pageCount);
            $dbBook->setStatus($book->status);
            //$dbBook->setPublishedDate($book->publishedDate ?? null);
            //$dbBook->setThumbnailUrl($book->thumbnailUrl);
            //$dbBook->setShortDescription($book->shortDescription);
            //$dbBook->setLongDescription($book->longDescription);
            $dbBook->setAuthors($book->authors);
            $dbBook->setCategories($book->categories);


            //$this->log($output, $id, $book, 'publishedDate');
            $this->log($output, $id, $book, 'shortDescription');
            $this->log($output, $id, $book, 'longDescription');
            $this->log($output, $id, $book, 'thumbnailUrl');
            //$output->writeln($id.': '.$book->authors);
            //$output->writeln($id.': '.$book->categories);
            //$output->writeln("");
            $em->persist($dbBook);
        }
        $em->flush();
        // var_dump($books);
        //print_r($books);

        return Command::SUCCESS;
    }
}
