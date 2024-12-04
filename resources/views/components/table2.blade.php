<div class="table-responsive mx-3">
    <table class="table table-flush" style="display: block; height: 300px; overflow: auto;">
        <thead class="thead-light" style="  position: sticky;top: 0;background-color:lightgray;">
            <tr>
                {{ $head ?? '' }}
            </tr>
        </thead>
        <tbody>
            {{ $body ?? '' }}
        </tbody>
    </table>
</div>