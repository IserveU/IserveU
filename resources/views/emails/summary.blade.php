  <table style="{{ $style['email-body_inner'] }}" align="center" width="570" cellpadding="0" cellspacing="0">
      <tr>
          <td style="{{ $fontFamily }} {{ $style['email-body_cell'] }}">

              <!-- Sections -->
              @foreach ($sections as $sectionName => $sectionContent)
                <h1 style="{{ $style['header-1'] }}">
                    {{$sectionName}}
                </h1>
                <table style="{{ $style['body_action'] }}" align="left" width="100%" cellpadding="0" cellspacing="0">
                      @foreach($sectionContent as $motion)
                      <tr>
                          <td rowspan="2" align="center">
                            @if($motion->rank  > 0)
                              <img style="{{ $style['large-thumb'] }}" src="{{url("/symbols/thumb-up.svg")}}" title="{{ $motion->motionOpenForVoting? "Currently Passing" : "Passed" }}" />

                            @elseif($motion->rank  < 0)
                              <img style="{{ $style['large-thumb'] }}" src="{{url("/symbols/thumb-down.svg")}}" title="{{ $motion->motionOpenForVoting? "Currently Failing" : "Failed" }}" />
                            @else
                              <img style="{{ $style['large-thumb'] }}" src="{{url("/symbols/thumbs-up-down.svg")}}" title="{{ $motion->motionOpenForVoting? "Currently Tied" : "Tie Vote" }}" />
                            @endif
                          </td>
                          <td align="left" colspan="3">
                            <a style="{{ $style['header-2'] }}" href="{{$token ? url('/#/reset-password/'.$token->token) : url('/#/motion/'.$motion->slug)}}">
                                {{$motion->title}}
                            </a>
                          </td>
                      </tr>
                      <tr>
                          <td align="left">
                            <p style="{{ $style['paragraph'] }}">
                              {{$motion->summary}}
                            </p>
                          </td>
                          <td>
                            @if($motion->motionOpenForVoting)
                              <a style="{{ $style['header-2'] }}" href="{{$token ? url('/#/reset-password/'.$token->token) : url('/#/motion/'.$motion->slug.'/vote/agree')}}">
                                <img style="{{ $style['small-thumb'] }}" src="{{url("/symbols/thumb-up.svg")}}" title="Agree with this" />
                              </a>
                            @endif
                          </td>
                          <td>
                            @if($motion->motionOpenForVoting)
                              <a style="{{ $style['header-2'] }}" href="{{$token ? url('/#/reset-password/'.$token->token) : url('/#/motion/'.$motion->slug.'/vote/disagree')}}">
                                <img style="{{ $style['small-thumb'] }}" src="{{ url("/symbols/thumb-down.svg")}}" title="Disagree with this" />
                              </a>
                            @endif($motion->motionOpenForVoting)

                          </td>
                      </tr>

                      @endforeach

                </table>

              @endforeach


          </td>
      </tr>
  </table>
