<?php

namespace Database\Seeders;

use App\Models\Recipe;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    public function run()
    {
        // Recipe 1: Biryani (already correct)
        Recipe::create([
            'title' => 'Biryani',
            'cuisine_type' => 'Indian',
            'cooking_time' => '1 hour 30 minutes',
            'servings' => '4-5 people',
            'youtube_video' => 'https://youtu.be/DOjNC_BkZiM?si=n63_N3l-qTD7zhfJ',
            'ingredients' => json_encode([
                'Basmati Rice – 2 cups',
                'Chicken (or Paneer for vegetarian version) – 500g',
                'Yogurt – 1/2 cup',
                'Onion – 2 large (thinly sliced)',
                'Tomatoes – 2 medium (chopped)',
                'Green Chilies – 2 (slit)',
                'Ginger-Garlic Paste – 1 tbsp',
                'Biryani Masala – 2 tbsp',
                'Turmeric Powder – 1/2 tsp',
                'Red Chili Powder – 1 tsp',
                'Garam Masala – 1 tsp',
                'Fresh Coriander Leaves – 1/4 cup (chopped)',
                'Fresh Mint Leaves – 1/4 cup (chopped)',
                'Salt – to taste',
                'Water – 4 cups',
                'Ghee or Oil – 3 tbsp',
                'Whole Spices (Cinnamon, Cloves, Cardamom) – 1-2 each',
                'Saffron – a pinch (soaked in warm milk)',
            ]),
            'instructions' => json_encode([
                'Marinate Chicken/Paneer: In a bowl, mix chicken or paneer with yogurt, ginger-garlic paste, biryani masala, red chili powder, turmeric, and salt. Set aside for 30 minutes.',
                'Cook Rice: In a pot, bring 4 cups of water to boil with whole spices. Add the basmati rice and cook until it\'s 70% done. Drain and set aside.',
                'Prepare the Biryani Masala: Heat ghee or oil in a pan, add onions, and sauté until golden brown. Add tomatoes, green chilies, and cook until soft. Add marinated chicken or paneer and cook until fully done.',
                'Layering: In a large pot, layer the cooked rice on top of the chicken/vegetable masala. Sprinkle saffron milk, fresh mint, and coriander leaves on top.',
                'Steam (Dum Cooking): Cover the pot with a tight lid and cook on low heat for 20-30 minutes to let the flavors meld.',
                'Serve: Garnish with fried onions (optional) and serve with raita or a side salad.',
            ]),
            'nutrition' => json_encode([
                'calories' => '500-600 kcal',
                'fat' => '15-20g',
                'protein' => '30-35g',
                'carbs' => '60-70g',
            ]),
        ]);

        // Recipe 2: Butter Chicken (already correct)
        Recipe::create([
            'title' => 'Butter Chicken',
            'cuisine_type' => 'Indian',
            'cooking_time' => '40 minutes',
            'servings' => '4 people',
            'youtube_video' => 'https://youtu.be/a03U45jFxOI?si=TDHUw7SxZXsCKcB-',
            'ingredients' => json_encode([
                'Chicken (boneless) – 500g',
                'Butter – 3 tbsp',
                'Fresh Cream – 1/2 cup',
                'Tomatoes – 3 (blended into puree)',
                'Onion – 1 large (finely chopped)',
                'Ginger-Garlic Paste – 1 tbsp',
                'Red Chili Powder – 1 tsp',
                'Garam Masala – 1 tsp',
                'Kasuri Methi – 1 tsp (dried fenugreek leaves)',
                'Salt – to taste',
                'Fresh Coriander Leaves – for garnish',
                'Water – 1/4 cup',
            ]),
            'instructions' => json_encode([
                'Marinate the Chicken: Mix chicken with ginger-garlic paste, red chili powder, salt, and some yogurt. Let it sit for 30 minutes.',
                'Cook Chicken: Heat butter in a pan and cook the marinated chicken until browned. Set aside.',
                'Prepare Gravy: In the same pan, sauté chopped onions until soft. Add ginger-garlic paste and cook for 2 minutes. Add tomato puree, kasuri methi, garam masala, and cook until the oil separates from the gravy.',
                'Add Cream: Add fresh cream and mix well. Add water to adjust the consistency of the gravy.',
                'Combine: Add the cooked chicken to the gravy and simmer for 10 minutes.',
                'Serve: Garnish with fresh coriander leaves and serve with naan or rice.',
            ]),
            'nutrition' => json_encode([
                'calories' => '400-450 kcal',
                'fat' => '30-35g',
                'protein' => '25-30g',
                'carbs' => '15-20g',
            ]),
        ]);

        // Recipe 3: Masala Dosa (FIXED with json_encode)
        Recipe::create([
            'title' => 'Masala Dosa',
            'cuisine_type' => 'South Indian',
            'cooking_time' => '45 minutes',
            'servings' => '4',
            'youtube_video' => 'https://youtu.be/mDqkxZ3UVzc?si=VbY36M6hnRy8jBYt',
            'ingredients' => json_encode([
                'Rice – 2 cups',
                'Urad Dal (Black Gram) – 1/2 cup',
                'Fenugreek Seeds – 1 tsp',
                'Water – as needed',
                'Salt – to taste',
                'Oil – for frying',
                'Potatoes – 4 medium (boiled and mashed)',
                'Onions – 2 (sliced)',
                'Green Chilies – 2 (chopped)',
                'Ginger – 1-inch piece (grated)',
                'Mustard Seeds – 1 tsp',
                'Turmeric Powder – 1/2 tsp',
                'Curry Leaves – 1 sprig',
                'Salt – to taste',
                'Fresh Coriander – for garnish',
            ]),
            'instructions' => json_encode([
                'Soak the Ingredients: Soak rice, urad dal, and fenugreek seeds in water for 4-6 hours.',
                'Make the Batter: Grind the soaked ingredients into a smooth batter. Let it ferment overnight or for 8 hours.',
                'Prepare the Filling: Heat oil in a pan, add mustard seeds, curry leaves, onions, green chilies, and ginger. Sauté until onions soften. Add boiled potatoes, turmeric, and salt. Mix well and set aside.',
                'Make Dosas: Heat a non-stick tawa, pour a ladle of batter, and spread it in a circular motion. Drizzle oil on the edges. Cook until golden brown.',
                'Stuff the Dosa: Place the prepared filling in the center and fold the dosa.',
                'Serve: Serve hot with coconut chutney and sambar.',
            ]),
            'nutrition' => json_encode([
                'calories' => '250-300 kcal',
                'fat' => '10-15g',
                'protein' => '7-10g',
                'carbs' => '35-40g',
            ]),
        ]);

        // Recipe 4: Chole Bhature (FIXED with json_encode)
        Recipe::create([
            'title' => 'Chole Bhature',
            'cuisine_type' => 'North Indian',
            'cooking_time' => '1 hour 15 minutes',
            'servings' => '4-5 people',
            'youtube_video' => 'https://youtu.be/M-ohmJswy6A?si=ioJRsgwZuM_ItJ-E',
            'ingredients' => json_encode([
                'Chickpeas – 1 1/2 cups (soaked overnight)',
                'Onion – 1 large (chopped)',
                'Tomatoes – 2 medium (chopped)',
                'Ginger-Garlic Paste – 1 tbsp',
                'Green Chilies – 2 (slit)',
                'Red Chili Powder – 1 tsp',
                'Garam Masala – 1 tsp',
                'Cumin Seeds – 1 tsp',
                'Asafoetida – a pinch',
                'Salt – to taste',
                'Fresh Coriander Leaves – for garnish',
                'Oil – 2 tbsp',
                'All-purpose Flour – 2 cups',
                'Semolina (Rava) – 2 tbsp',
                'Baking Powder – 1/2 tsp',
                'Yogurt – 1/4 cup',
                'Salt – to taste',
                'Water – as needed',
                'Oil – for frying',
            ]),
            'instructions' => json_encode([
                'Prepare the Chole: In a pressure cooker, cook soaked chickpeas with water and salt for 6-7 whistles. Once done, set aside.',
                'Cook the Gravy: Heat oil in a pan. Add cumin seeds and asafoetida. Sauté onions until golden brown, then add ginger-garlic paste and sauté further. Add tomatoes, red chili powder, and garam masala, and cook until tomatoes soften.',
                'Simmer the Chole: Add cooked chickpeas to the gravy, along with some water to adjust the consistency. Let it simmer for 15 minutes, then garnish with coriander leaves.',
                'Prepare the Bhature Dough: Mix flour, semolina, baking powder, and salt in a bowl. Add yogurt and knead the dough using water to make a soft, smooth dough. Let it rest for 30 minutes.',
                'Fry the Bhature: Divide dough into small balls and roll them out into small discs. Heat oil in a pan and deep fry the bhature until golden brown and puffed up.',
                'Serve: Serve the chole hot with bhature, garnished with onion and lemon wedges.',
            ]),
            'nutrition' => json_encode([
                'calories' => '500-600 kcal',
                'fat' => '20-25g',
                'protein' => '15-20g',
                'carbs' => '70-80g',
            ]),
        ]);

        // Recipe 5: Rogan Josh (FIXED with json_encode)
        Recipe::create([
            'title' => 'Rogan Josh',
            'cuisine_type' => 'Kashmiri',
            'cooking_time' => '1 hour 30 minutes',
            'servings' => '4-5 people',
            'youtube_video' => 'https://youtu.be/NZVo32n7iS8?si=Er2DnKHfnPKG0Boz',
            'ingredients' => json_encode([
                'Lamb – 500g (cut into pieces)',
                'Onion – 2 large (sliced)',
                'Tomatoes – 2 medium (chopped)',
                'Yogurt – 1/4 cup',
                'Garlic – 4 cloves (crushed)',
                'Ginger – 1-inch piece (grated)',
                'Red Chili Powder – 1 tsp',
                'Garam Masala – 1 tsp',
                'Kashmiri Red Chili Powder – 1 tbsp',
                'Cinnamon Stick – 1',
                'Cloves – 4',
                'Bay Leaves – 2',
                'Salt – to taste',
                'Fresh Coriander – for garnish',
                'Oil – 3 tbsp',
                'Water – as needed',
            ]),
            'instructions' => json_encode([
                'Prepare the Meat: Heat oil in a large pan. Add whole spices (cinnamon, cloves, bay leaves) and sauté for a minute.',
                'Cook Onions: Add onions and sauté until golden brown. Add garlic and ginger, and sauté for 2 more minutes.',
                'Add Meat: Add the lamb pieces and cook until they turn brown on all sides.',
                'Cook Tomatoes: Add chopped tomatoes and cook until they turn soft.',
                'Prepare Gravy: Add yogurt, red chili powder, Kashmiri chili powder, garam masala, and salt. Cook until the oil separates from the masala.',
                'Simmer: Add water, cover, and cook the lamb on low heat for 45 minutes until tender.',
                'Serve: Garnish with fresh coriander and serve with naan or rice.',
            ]),
            'nutrition' => json_encode([
                'calories' => '350-400 kcal',
                'fat' => '25-30g',
                'protein' => '30-35g',
                'carbs' => '10-15g',
            ]),
        ]);

        // Recipe 6: Paneer Tikka (FIXED with json_encode)
        Recipe::create([
            'title' => 'Paneer Tikka',
            'cuisine_type' => 'Indian',
            'cooking_time' => '30 minutes',
            'servings' => '4',
            'youtube_video' => 'https://youtu.be/abIuyROpyuE?si=jdLJLVXsHC5LDF0z',
            'ingredients' => json_encode([
                'Paneer (Cottage Cheese) – 250g (cubed)',
                'Yogurt – 1/4 cup',
                'Red Chili Powder – 1 tsp',
                'Turmeric Powder – 1/2 tsp',
                'Garam Masala – 1 tsp',
                'Lemon Juice – 1 tbsp',
                'Cumin Powder – 1 tsp',
                'Ginger-Garlic Paste – 1 tbsp',
                'Salt – to taste',
                'Fresh Coriander Leaves – for garnish',
                'Oil – for grilling',
            ]),
            'instructions' => json_encode([
                'Prepare the Marinade: In a bowl, mix yogurt, red chili powder, turmeric, garam masala, cumin powder, ginger-garlic paste, lemon juice, and salt.',
                'Marinate the Paneer: Coat the paneer cubes with the marinade and refrigerate for 20-30 minutes.',
                'Grill the Paneer: Heat oil in a grill pan or on a barbecue. Grill the paneer cubes until golden and slightly charred on all sides.',
                'Serve: Garnish with fresh coriander and serve with mint chutney and salad.',
            ]),
            'nutrition' => json_encode([
                'calories' => '250-300 kcal',
                'fat' => '20-25g',
                'protein' => '15-20g',
                'carbs' => '5-10g',
            ]),
        ]);

        // Recipe 7: Samosa (FIXED with json_encode)
        Recipe::create([
            'title' => 'Samosa',
            'cuisine_type' => 'Indian',
            'cooking_time' => '45 minutes',
            'servings' => '6-8 samosas',
            'youtube_video' => 'https://youtu.be/lfJ5TjUAnJM?si=IWFy9B7BWzF1M0lJ',
            'ingredients' => json_encode([
                'Potatoes – 4 medium (boiled and mashed)',
                'Green Peas – 1/2 cup (boiled)',
                'Onion – 1 (finely chopped)',
                'Green Chilies – 2 (chopped)',
                'Garam Masala – 1 tsp',
                'Coriander Powder – 1 tsp',
                'Cumin Seeds – 1 tsp',
                'Salt – to taste',
                'All-purpose Flour – 1 cup',
                'Oil – for frying',
            ]),
            'instructions' => json_encode([
                'Prepare the Filling: Heat oil in a pan, add cumin seeds, then onions, and cook until soft. Add boiled potatoes, peas, green chilies, garam masala, coriander powder, and salt. Cook for 5 minutes and set aside.',
                'Make the Dough: Mix flour and salt in a bowl. Add water and knead into a smooth dough. Let it rest for 15 minutes.',
                'Shape the Samosas: Divide the dough into small balls, roll them into circles, and cut them in half. Form a cone and stuff with the filling.',
                'Fry the Samosas: Heat oil in a pan and deep fry the samosas until golden brown.',
                'Serve: Serve hot with tamarind or mint chutney.',
            ]),
            'nutrition' => json_encode([
                'calories' => '150-200 kcal',
                'fat' => '7-10g',
                'protein' => '3-4g',
                'carbs' => '20-25g',
            ]),
        ]);

        // Recipe 8: Vada Pav (FIXED with json_encode)
        Recipe::create([
            'title' => 'Vada Pav',
            'cuisine_type' => 'Mumbai Street Food',
            'cooking_time' => '45 minutes',
            'servings' => '4',
            'youtube_video' => 'https://youtu.be/dBa9B-P3NWM?si=gPQUWCujQnv-4PsN',
            'ingredients' => json_encode([
                'Potatoes – 4 (boiled and mashed)',
                'Green Chilies – 2 (chopped)',
                'Ginger – 1-inch piece (grated)',
                'Garlic – 3 cloves (crushed)',
                'Mustard Seeds – 1 tsp',
                'Turmeric Powder – 1/2 tsp',
                'Asafoetida – a pinch',
                'Salt – to taste',
                'Whole Wheat Buns (Pav) – 4',
                'Besan (Chickpea Flour) – 1 cup',
                'Rice Flour – 2 tbsp',
                'Baking Soda – a pinch',
                'Oil – for frying',
            ]),
            'instructions' => json_encode([
                'Prepare the Filling: Heat oil in a pan and add mustard seeds, asafoetida, green chilies, ginger, and garlic. Add turmeric powder and mashed potatoes. Cook for 5 minutes and set aside.',
                'Prepare the Batter: Mix besan, rice flour, baking soda, and salt with water to make a thick batter.',
                'Shape the Vadas: Form small balls of the potato mixture.',
                'Fry the Vadas: Dip each ball into the batter and deep fry until golden brown.',
                'Assemble the Vada Pav: Slice the pav and stuff with a vada. Serve with chutneys.',
            ]),
            'nutrition' => json_encode([
                'calories' => '250-300 kcal',
                'fat' => '12-15g',
                'protein' => '5-6g',
                'carbs' => '30-35g',
            ]),
        ]);

        // Recipe 9: Pav Bhaji (FIXED with json_encode)
        Recipe::create([
            'title' => 'Pav Bhaji',
            'cuisine_type' => 'Mumbai Street Food',
            'cooking_time' => '45 minutes',
            'servings' => '4-5 people',
            'youtube_video' => 'https://youtu.be/dz6eh3U5zEM?si=OCuX9tIGcTKOnn9Q',
            'ingredients' => json_encode([
                'Potatoes – 3 large (boiled and mashed)',
                'Cauliflower – 1 cup (chopped)',
                'Carrot – 1 (chopped)',
                'Green Beans – 1/2 cup (chopped)',
                'Onion – 1 large (finely chopped)',
                'Tomatoes – 2 medium (chopped)',
                'Green Bell Pepper – 1 (chopped)',
                'Garlic – 4 cloves (minced)',
                'Ginger – 1-inch piece (grated)',
                'Pav Bhaji Masala – 2 tbsp',
                'Red Chili Powder – 1 tsp',
                'Salt – to taste',
                'Butter – 2 tbsp',
                'Lemon Juice – 1 tbsp',
                'Fresh Coriander Leaves – for garnish',
                'Pav (bread rolls) – 8',
                'Oil – for cooking',
            ]),
            'instructions' => json_encode([
                'Cook Vegetables: In a pressure cooker, cook potatoes, cauliflower, carrot, and green beans with water until soft (4-5 whistles).',
                'Prepare the Gravy: Heat oil in a pan, add chopped onions and sauté until golden brown. Add garlic, ginger, and green bell pepper, and cook for 2 minutes.',
                'Cook Tomatoes: Add chopped tomatoes, red chili powder, and pav bhaji masala. Cook until tomatoes soften and oil separates from the mixture.',
                'Mash the Vegetables: Add the boiled vegetables to the pan, mash them with a masher, and mix well. Add water if needed to adjust consistency.',
                'Cook the Bhaji: Simmer the bhaji for 10-15 minutes, stirring occasionally.',
                'Prepare the Pav: Slice the pav and toast them with butter on a hot griddle until golden brown.',
                'Serve: Serve the bhaji with toasted pav, garnished with fresh coriander leaves and a squeeze of lemon juice.',
            ]),
            'nutrition' => json_encode([
                'calories' => '350-400 kcal',
                'fat' => '15-20g',
                'protein' => '7-10g',
                'carbs' => '50-55g',
            ]),
        ]);

        // Recipe 10: Gulab Jamun (FIXED with json_encode)
        Recipe::create([
            'title' => 'Gulab Jamun',
            'cuisine_type' => 'Indian Dessert',
            'cooking_time' => '1 hour',
            'servings' => '10-12 pieces',
            'youtube_video' => 'https://youtu.be/An5NHoUzQQg?si=6HIQPcyvWD-GhKfc',
            'ingredients' => json_encode([
                'Sugar – 2 1/2 cups',
                'Water – 1 1/2 cups',
                'Cardamom Powder – 1/2 tsp',
                'Saffron Strands – a pinch (optional)',
                'Milk Powder – 1 cup',
                'All-purpose Flour – 1/4 cup',
                'Baking Soda – 1/4 tsp',
                'Ghee (clarified butter) – 2 tbsp',
                'Milk – as needed (for kneading)',
                'Ghee or Oil – for frying',
            ]),
            'instructions' => json_encode([
                'Prepare the Sugar Syrup: In a saucepan, combine sugar and water. Heat it on medium flame and cook until it reaches a one-thread consistency. Add cardamom powder and saffron, then set aside.',
                'Make the Dough: In a bowl, mix milk powder, all-purpose flour, and baking soda. Add ghee and milk, and knead into a smooth, soft dough. Let it rest for 10-15 minutes.',
                'Shape the Gulab Jamuns: Take small portions of dough and roll them into smooth balls. Ensure there are no cracks to prevent them from breaking during frying.',
                'Fry the Gulab Jamuns: Heat ghee or oil in a pan over low flame. Fry the gulab jamuns in batches until golden brown, making sure to turn them gently for even cooking.',
                'Soak in Syrup: Once fried, drain the gulab jamuns from the oil and immediately drop them into the warm sugar syrup. Let them soak for 30 minutes.',
                'Serve: Serve the gulab jamuns warm, garnished with a few saffron strands if desired.',
            ]),
            'nutrition' => json_encode([
                'calories' => '150-180 kcal (per piece)',
                'fat' => '7-8g',
                'protein' => '2-3g',
                'carbs' => '20-25g',
            ]),
        ]);
    }
}
