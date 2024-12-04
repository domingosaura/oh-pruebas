<?php

namespace App\Http\Livewire\LaravelExamples\Profile;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Edit extends Component
{

    use WithFileUploads;

    public User $user;

    public $picture;

    public $confirmationPassword='';
    public $new_password="";
    public $old_password='';

    protected function rules(){
    return [
        'user.name' => 'required',
        'user.email' => 'required|email|unique:users,email,'.$this->user->id,
        'user.phone' => 'required| max:10',
        'user.location' => 'required',
    ];
}

    public function mount() { 
        $this->user = auth()->user();
    }

    public function updated($propertyName){

        $this->validateOnly($propertyName);

    }

    public function update()
    {
        $this->validate();


        if ($this->picture) {

            $this->validate([
                'picture' => 'mimes:jpg,jpeg,png,bmp,tiff |max:4096',
            ]);

            $currentAvatar = auth()->user()->picture;

            if($currentAvatar !== 'profile/avatar.jpg' && $currentAvatar !== 'profile/avatar2.jpg' && $currentAvatar !== 'profile/avatar3.jpg' && !empty($currentAvatar)){

                unlink(storage_path('app/public/'.$currentAvatar));
                $this->user->update([
                    'picture' => $this->picture->store('profile', 'public')
                ]);
            }
            else{
                $this->user->update([
                    'picture' => $this->picture->store('profile', 'public')
                ]);
            }

        }
        $this->user->save();

        return back()->withStatus('Profile successfully updated.');
    }


    public function passwordUpdate(){

        $this->validate([ 
            'old_password' => 'required',
            'new_password' => 'required|min:7|same:confirmationPassword',
        ]);
        
        $hashedPassword = auth()->user()->password;

        if (Hash::check($this->old_password , $hashedPassword)) {
            if (!Hash::check($this->new_password , $hashedPassword))
            {
                $users = User::findorFail(auth()->user()->id);
                $users->password = $this->new_password;
                $users->save();
                return back()->with(['success'=>'Password successfully updated.']);
            }
            else{
                return back()->with(['error' =>"New password can not be the old password!"]);
            } 
        }
        else{
            return back()->with(['error' =>"Old password doesn't match"]);
        }
    } 

    public function render()
    {
        return view('livewire.laravel-examples.profile.edit');
    }
}
