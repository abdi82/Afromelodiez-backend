<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>
<form method="POST" action="{{ route('Register_admin') }}" enctype='multipart/form-data'>
            @csrf

                <div class="col-md-12 ">
                    <label for="CatgoryName">Enter Name</label>
                    <input type="text" placeholder="Enter Name" name="name" required>
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Email</label>
                    <input type="email" placeholder="Enter Email" name="email" required>
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Password </label>
                    <input type="password" placeholder="Password" name="password" required>
                </div>
                <div class="col-md-12 ">
                    <label for="CatgoryName">Confirm Password</label>
                    <input type="password" placeholder="Confirm Password" name="password_confirmation" required>
                </div>
                <div class="col-md-12 ">
                <label for="CatgoryName"> Country </label>
                <select name="country" class="countries order-alpha" id="countryId" multiple="multiple">
                    <option value="">Select Country</option>
                </select>
                </div>
                <div class="col-md-12 ">
                <label for="CatgoryName"> State </label>
                <select name="state" class="states order-alpha" id="stateId">
                    <option value="">Select State</option>
                </select>
                </div>
                <div class="col-md-12 "> 
                <label for="CatgoryName"> City </label>
                <select name="city" class="cities order-alpha" id="cityId">
                    <option value="">Select City</option>
                </select>
                </div> 
                <div class="col-md-12">
                <label for="description">Description </label>
                  <textarea id="description" name="description" rows="4" cols="50"> 
                </textarea>
                </div> 
                <div class="col-md-12 ">
                    <label for="CatgoryName">Profile Image </label>
                    <input type="file" name="image">
                </div> 
                 <div class="col-md-12 "> 
                <input type="hidden" name="user_role" value="admin">
                <input type="submit" value="Save">
                </div>

         </form>
         <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> 
<script src="//geodata.solutions/includes/countrystatecity.js"></script>

    </x-jet-authentication-card>
</x-guest-layout>
