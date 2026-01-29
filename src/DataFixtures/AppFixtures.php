<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    // Service de hachage 
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- 1. CREATION DES UTILISATEURS ---

        // Compte Admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $password = $this->hasher->hashPassword($admin, 'admin1234');
        $admin->setPassword($password);
        $manager->persist($admin);

        // Compte Alexandre
        $user = new User();
        $user->setUsername('alexandre');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'motdepasse'));
        $manager->persist($user);

        // Compte Candice
        $user = new User();
        $user->setUsername('candice');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'plop123'));
        $manager->persist($user);

        // Compte Yanel
        $user = new User();
        $user->setUsername('yanel');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, 'nimportequoi'));
        $manager->persist($user);

         // --- 2. CREATION DES CATEGORIES ---
         $some_categories=['Jeux-vidéos', 'Développement web', 'Voyage', 'Technologie'];
         $categories=[];

         foreach($some_categories as $name){
            $category=new Category();
            $category->setName($name);
            $manager->persist($category);
            $categories[]=$category;
         }

        // --- 3. CREATION DES ARTICLES ---
        $some_titles=[
            'Open the door, let me out !', 
            'Mouse cats are the world',
            'Rub whiskers on bare skin act innocent',
            'Lay on arms while you\'re using the keyboard',
            'Meow meow mama'];


        $some_contents=[
            'Jump up to edge of bath, fall in then scramble in a mad panic to get out caticus cuteicus chase mice scratch the postman wake up lick paw wake up owner meow meow trip owner up in kitchen i want food kitty kitty pussy cat doll and found somthing move i bite it tail. Lie in the sink all day human is washing you why halp oh the horror flee scratch hiss bite for eat an easter feather as if it were a bird then burp victoriously, but tender for whatever. Small kitty warm kitty little balls of fur hiiiiiiiiii feed me now so tuxedo cats always looking dapper lick butt cattt catt cattty cat being a cat destroy dog. Lick sellotape. Cry louder at reflection walk on a keyboard i hate cucumber pls dont throw it at me and human give me attention meow. Cat meoooow i iz master of hoomaan, not hoomaan master of i, oooh damn dat dog',
            'I bet my nine lives on you-oooo-ooo-hooo more napping, more napping all the napping is exhausting kitty loves pigs cats are the world so blow up sofa in 3 seconds so gnaw the corn cob. Meow meow you are my owner so here is a dead bird purr while eating or head nudges destroy the blinds i love cats i am one wake up scratch humans leg for food then purr then i have a and relax i is not fat, i is fluffy, jump up to edge of bath, fall in then scramble in a mad panic to get out. Disappear for four days and return home with an expensive injury; bite the vet prow?? ew dog you drink from the toilet, yum yum warm milk hotter pls, ouch too hot and ask to be pet then attack owners hand cat playing a fiddle in hey diddle diddle?',
            'Naughty running cat. Hey! you there, with the hands. Drink water out of the faucet meow and walk away yet fish i must find my red catnip fishy fish stick butt in face. Stare at owner accusingly then wink lie on your belly and purr when you are asleep yet destroy the blinds yet get scared by doggo also cucumerro demand to have some of whatever the human is cooking, then sniff the offering and walk away. Do doodoo in the litter-box, clickityclack on the piano, be frumpygrumpy making bread on the bathrobe for the fat cat sat on the mat bat away with paws for bird bird bird bird bird bird human why take bird out i could have eaten that the fat cat sat on the mat bat away with paws fooled again thinking the dog likes me but purr like an angel.',
            'Chase dog then run away. All of a sudden cat goes crazy cats making all the muffins but sleeping in the box for run around the house at 4 in the morning, but good morning sunshine this cat happen now, it was too purr-fect!!!. Run as fast as i can into another room for no reason dead stare with ears cocked or sit in a box for hours yet the door is opening! how exciting oh, it\'s you, meh gnaw the corn cob. Kitty time need to check on human, have not seen in an hour might be dead oh look, human is alive, hiss at human, feed me i want to go outside let me go outside nevermind inside is better use lap as chair go into a room to decide you didn\'t want to be in there anyway. Kitty run to human with blood on mouth from frenzied attack on poor innocent mouse, don\'t i look cute?',
            'Licks your face. Chew master\'s slippers. Purr like a car engine oh yes, there is my human slave woman she does best pats ever that all i like about her hiss meow why use post when this sofa is here meeeeouw instantly break out into full speed gallop across the house for no reason. Russian blue. Sleep on keyboard thug cat for lick arm hair or lasers are tiny mice. Destroy house in 5 seconds eat the fat cats food rub butt on table. Eat a rug and furry furry hairs everywhere oh no human coming lie on counter don\'t get off counter meow if it fits, i sits chew foot. Claws in your leg chase the pig around the house groom yourself 4 hours - checked, have your beauty sleep 18 hours - checked, be fabulous for the rest of the day - checked, for get poop stuck in paws jumping out of litter box and run around the house scream meowing and smearing hot cat mud all over. Whatever paw your face to wake you up in the morning yet more napping, more napping all the napping is exhausting chirp at birds, chill on the couch table but hate dog, and cats secretly make all the worlds muffins.'];


        for ($i = 1; $i <= 15; $i++) {
            $randomTitle = $some_titles[array_rand($some_titles)];
            $randomContent = $some_contents[array_rand($some_contents)];

        $article = new Article();
        $article->setTitle($randomTitle)
                ->setContent($randomContent);

        $article->setCategory($categories[array_rand($categories)]);

        $manager->persist($article);
    }
        $manager->flush();
    }
}
