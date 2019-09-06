#include <iostream>
#include<fstream>
using namespace std;
ifstream f("deque.in");
ofstream g("deque.out");
int a[5000001],Deque[5000001],n,k,i,Front,Back;
long long s=0;
int main()
{
    f>>n>>k;
    for(i=1;i<=n;i++)
        f>>a[i];
    Front=1;
    Back=0;
    for(i=1;i<=n;i++)
    {
        while(Front<=Back && a[i]<=a[Deque[Back]])
            Back--;
        Deque[++Back]=i;
        if(Deque[Front]==i-k)
            Front++;
        if(i>=k)
            s+=a[Deque[Front]];
    }
    g<<s;
}
